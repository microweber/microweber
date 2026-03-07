<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../league/csv/src/Query/PredicateCombinator.php-PHPStan\BetterReflection\Reflection\ReflectionClass-League\Csv\Query\PredicateCombinator
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-8f885e673b2097af7598603cd8d24b85248122e80bb36cdd085643f49beb5a8f-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'League\\Csv\\Query\\PredicateCombinator',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../league/csv/src/Query/PredicateCombinator.php',
      ),
    ),
    'namespace' => 'League\\Csv\\Query',
    'name' => 'League\\Csv\\Query\\PredicateCombinator',
    'shortName' => 'PredicateCombinator',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @phpstan-type Condition Predicate|Closure(mixed, array-key): bool
 * @phpstan-type ConditionExtended Predicate|Closure(mixed, array-key): bool|callable(mixed, array-key): bool
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 22,
    'endLine' => 71,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'League\\Csv\\Query\\Predicate',
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
      'and' => 
      array (
        'name' => 'and',
        'parameters' => 
        array (
          'predicates' => 
          array (
            'name' => 'predicates',
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
                      'name' => 'League\\Csv\\Query\\Predicate',
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
            'startLine' => 34,
            'endLine' => 34,
            'startColumn' => 25,
            'endColumn' => 56,
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
 * Return an instance with the specified predicates
 * joined together and with the current predicate
 * using the AND Logical operator.
 *
 * This method MUST retain the state of the current instance, and return
 * an instance that contains the specified changes.
 *
 * @param Condition ...$predicates
 */',
        'startLine' => 34,
        'endLine' => 34,
        'startColumn' => 5,
        'endColumn' => 64,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'League\\Csv\\Query',
        'declaringClassName' => 'League\\Csv\\Query\\PredicateCombinator',
        'implementingClassName' => 'League\\Csv\\Query\\PredicateCombinator',
        'currentClassName' => 'League\\Csv\\Query\\PredicateCombinator',
        'aliasName' => NULL,
      ),
      'or' => 
      array (
        'name' => 'or',
        'parameters' => 
        array (
          'predicates' => 
          array (
            'name' => 'predicates',
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
                      'name' => 'League\\Csv\\Query\\Predicate',
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
            'startLine' => 46,
            'endLine' => 46,
            'startColumn' => 24,
            'endColumn' => 55,
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
 * Return an instance with the specified predicates
 * joined together and with the current predicate
 * using the OR Logical operator.
 *
 * This method MUST retain the state of the current instance, and return
 * an instance that contains the specified changes.
 *
 * @param Condition ...$predicates
 */',
        'startLine' => 46,
        'endLine' => 46,
        'startColumn' => 5,
        'endColumn' => 63,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'League\\Csv\\Query',
        'declaringClassName' => 'League\\Csv\\Query\\PredicateCombinator',
        'implementingClassName' => 'League\\Csv\\Query\\PredicateCombinator',
        'currentClassName' => 'League\\Csv\\Query\\PredicateCombinator',
        'aliasName' => NULL,
      ),
      'not' => 
      array (
        'name' => 'not',
        'parameters' => 
        array (
          'predicates' => 
          array (
            'name' => 'predicates',
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
                      'name' => 'League\\Csv\\Query\\Predicate',
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
            'startLine' => 58,
            'endLine' => 58,
            'startColumn' => 25,
            'endColumn' => 56,
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
 * Return an instance with the specified predicates
 * joined together and with the current predicate
 * using the NOT Logical operator.
 *
 * This method MUST retain the state of the current instance, and return
 * an instance that contains the specified changes.
 *
 * @param Condition ...$predicates
 */',
        'startLine' => 58,
        'endLine' => 58,
        'startColumn' => 5,
        'endColumn' => 64,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'League\\Csv\\Query',
        'declaringClassName' => 'League\\Csv\\Query\\PredicateCombinator',
        'implementingClassName' => 'League\\Csv\\Query\\PredicateCombinator',
        'currentClassName' => 'League\\Csv\\Query\\PredicateCombinator',
        'aliasName' => NULL,
      ),
      'xor' => 
      array (
        'name' => 'xor',
        'parameters' => 
        array (
          'predicates' => 
          array (
            'name' => 'predicates',
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
                      'name' => 'League\\Csv\\Query\\Predicate',
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
            'startLine' => 70,
            'endLine' => 70,
            'startColumn' => 25,
            'endColumn' => 56,
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
 * Return an instance with the specified predicates
 * joined together and with the current predicate
 * using the XOR Logical operator.
 *
 * This method MUST retain the state of the current instance, and return
 * an instance that contains the specified changes.
 *
 * @param Condition ...$predicates
 */',
        'startLine' => 70,
        'endLine' => 70,
        'startColumn' => 5,
        'endColumn' => 64,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'League\\Csv\\Query',
        'declaringClassName' => 'League\\Csv\\Query\\PredicateCombinator',
        'implementingClassName' => 'League\\Csv\\Query\\PredicateCombinator',
        'currentClassName' => 'League\\Csv\\Query\\PredicateCombinator',
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