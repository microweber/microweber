<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../mockery/mockery/library/Mockery/LegacyMockInterface.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Mockery\LegacyMockInterface
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-149e162b72d540cf6b2704858f58f46c692b08a483152d29b7251b4fa0a231a1-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Mockery\\LegacyMockInterface',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../mockery/mockery/library/Mockery/LegacyMockInterface.php',
      ),
    ),
    'namespace' => 'Mockery',
    'name' => 'Mockery\\LegacyMockInterface',
    'shortName' => 'LegacyMockInterface',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 16,
    'endLine' => 258,
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
      'byDefault' => 
      array (
        'name' => 'byDefault',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * In the event shouldReceive() accepting an array of methods/returns
 * this method will switch them from normal expectations to default
 * expectations
 *
 * @return self
 */',
        'startLine' => 25,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 32,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Mockery',
        'declaringClassName' => 'Mockery\\LegacyMockInterface',
        'implementingClassName' => 'Mockery\\LegacyMockInterface',
        'currentClassName' => 'Mockery\\LegacyMockInterface',
        'aliasName' => NULL,
      ),
      'makePartial' => 
      array (
        'name' => 'makePartial',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Set mock to defer unexpected methods to its parent if possible
 *
 * @return self
 */',
        'startLine' => 32,
        'endLine' => 32,
        'startColumn' => 5,
        'endColumn' => 34,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Mockery',
        'declaringClassName' => 'Mockery\\LegacyMockInterface',
        'implementingClassName' => 'Mockery\\LegacyMockInterface',
        'currentClassName' => 'Mockery\\LegacyMockInterface',
        'aliasName' => NULL,
      ),
      'mockery_allocateOrder' => 
      array (
        'name' => 'mockery_allocateOrder',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Fetch the next available allocation order number
 *
 * @return int
 */',
        'startLine' => 39,
        'endLine' => 39,
        'startColumn' => 5,
        'endColumn' => 44,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Mockery',
        'declaringClassName' => 'Mockery\\LegacyMockInterface',
        'implementingClassName' => 'Mockery\\LegacyMockInterface',
        'currentClassName' => 'Mockery\\LegacyMockInterface',
        'aliasName' => NULL,
      ),
      'mockery_findExpectation' => 
      array (
        'name' => 'mockery_findExpectation',
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
            'startLine' => 51,
            'endLine' => 51,
            'startColumn' => 45,
            'endColumn' => 51,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'args' => 
          array (
            'name' => 'args',
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
            'startLine' => 51,
            'endLine' => 51,
            'startColumn' => 54,
            'endColumn' => 64,
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
 * Find an expectation matching the given method and arguments
 *
 * @template TMixed
 *
 * @param string        $method
 * @param array<TMixed> $args
 *
 * @return null|Expectation
 */',
        'startLine' => 51,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 66,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Mockery',
        'declaringClassName' => 'Mockery\\LegacyMockInterface',
        'implementingClassName' => 'Mockery\\LegacyMockInterface',
        'currentClassName' => 'Mockery\\LegacyMockInterface',
        'aliasName' => NULL,
      ),
      'mockery_getContainer' => 
      array (
        'name' => 'mockery_getContainer',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Return the container for this mock
 *
 * @return Container
 */',
        'startLine' => 58,
        'endLine' => 58,
        'startColumn' => 5,
        'endColumn' => 43,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Mockery',
        'declaringClassName' => 'Mockery\\LegacyMockInterface',
        'implementingClassName' => 'Mockery\\LegacyMockInterface',
        'currentClassName' => 'Mockery\\LegacyMockInterface',
        'aliasName' => NULL,
      ),
      'mockery_getCurrentOrder' => 
      array (
        'name' => 'mockery_getCurrentOrder',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get current ordered number
 *
 * @return int
 */',
        'startLine' => 65,
        'endLine' => 65,
        'startColumn' => 5,
        'endColumn' => 46,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Mockery',
        'declaringClassName' => 'Mockery\\LegacyMockInterface',
        'implementingClassName' => 'Mockery\\LegacyMockInterface',
        'currentClassName' => 'Mockery\\LegacyMockInterface',
        'aliasName' => NULL,
      ),
      'mockery_getExpectationCount' => 
      array (
        'name' => 'mockery_getExpectationCount',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Gets the count of expectations for this mock
 *
 * @return int
 */',
        'startLine' => 72,
        'endLine' => 72,
        'startColumn' => 5,
        'endColumn' => 50,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Mockery',
        'declaringClassName' => 'Mockery\\LegacyMockInterface',
        'implementingClassName' => 'Mockery\\LegacyMockInterface',
        'currentClassName' => 'Mockery\\LegacyMockInterface',
        'aliasName' => NULL,
      ),
      'mockery_getExpectationsFor' => 
      array (
        'name' => 'mockery_getExpectationsFor',
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
            'startLine' => 81,
            'endLine' => 81,
            'startColumn' => 48,
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
        'docComment' => '/**
 * Return the expectations director for the given method
 *
 * @param string $method
 *
 * @return null|ExpectationDirector
 */',
        'startLine' => 81,
        'endLine' => 81,
        'startColumn' => 5,
        'endColumn' => 56,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Mockery',
        'declaringClassName' => 'Mockery\\LegacyMockInterface',
        'implementingClassName' => 'Mockery\\LegacyMockInterface',
        'currentClassName' => 'Mockery\\LegacyMockInterface',
        'aliasName' => NULL,
      ),
      'mockery_getGroups' => 
      array (
        'name' => 'mockery_getGroups',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Fetch array of ordered groups
 *
 * @return array<string,int>
 */',
        'startLine' => 88,
        'endLine' => 88,
        'startColumn' => 5,
        'endColumn' => 40,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Mockery',
        'declaringClassName' => 'Mockery\\LegacyMockInterface',
        'implementingClassName' => 'Mockery\\LegacyMockInterface',
        'currentClassName' => 'Mockery\\LegacyMockInterface',
        'aliasName' => NULL,
      ),
      'mockery_getMockableMethods' => 
      array (
        'name' => 'mockery_getMockableMethods',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return string[]
 */',
        'startLine' => 93,
        'endLine' => 93,
        'startColumn' => 5,
        'endColumn' => 49,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Mockery',
        'declaringClassName' => 'Mockery\\LegacyMockInterface',
        'implementingClassName' => 'Mockery\\LegacyMockInterface',
        'currentClassName' => 'Mockery\\LegacyMockInterface',
        'aliasName' => NULL,
      ),
      'mockery_getMockableProperties' => 
      array (
        'name' => 'mockery_getMockableProperties',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return array
 */',
        'startLine' => 98,
        'endLine' => 98,
        'startColumn' => 5,
        'endColumn' => 52,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Mockery',
        'declaringClassName' => 'Mockery\\LegacyMockInterface',
        'implementingClassName' => 'Mockery\\LegacyMockInterface',
        'currentClassName' => 'Mockery\\LegacyMockInterface',
        'aliasName' => NULL,
      ),
      'mockery_getName' => 
      array (
        'name' => 'mockery_getName',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Return the name for this mock
 *
 * @return string
 */',
        'startLine' => 105,
        'endLine' => 105,
        'startColumn' => 5,
        'endColumn' => 38,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Mockery',
        'declaringClassName' => 'Mockery\\LegacyMockInterface',
        'implementingClassName' => 'Mockery\\LegacyMockInterface',
        'currentClassName' => 'Mockery\\LegacyMockInterface',
        'aliasName' => NULL,
      ),
      'mockery_init' => 
      array (
        'name' => 'mockery_init',
        'parameters' => 
        array (
          'container' => 
          array (
            'name' => 'container',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 114,
                'endLine' => 114,
                'startTokenPos' => 179,
                'startFilePos' => 2462,
                'endTokenPos' => 179,
                'endFilePos' => 2465,
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
                      'name' => 'Mockery\\Container',
                      'isIdentifier' => false,
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
            'startLine' => 114,
            'endLine' => 114,
            'startColumn' => 34,
            'endColumn' => 61,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'partialObject' => 
          array (
            'name' => 'partialObject',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 114,
                'endLine' => 114,
                'startTokenPos' => 186,
                'startFilePos' => 2485,
                'endTokenPos' => 186,
                'endFilePos' => 2488,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 114,
            'endLine' => 114,
            'startColumn' => 64,
            'endColumn' => 84,
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
 * Alternative setup method to constructor
 *
 * @param object $partialObject
 *
 * @return void
 */',
        'startLine' => 114,
        'endLine' => 114,
        'startColumn' => 5,
        'endColumn' => 86,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Mockery',
        'declaringClassName' => 'Mockery\\LegacyMockInterface',
        'implementingClassName' => 'Mockery\\LegacyMockInterface',
        'currentClassName' => 'Mockery\\LegacyMockInterface',
        'aliasName' => NULL,
      ),
      'mockery_isAnonymous' => 
      array (
        'name' => 'mockery_isAnonymous',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return bool
 */',
        'startLine' => 119,
        'endLine' => 119,
        'startColumn' => 5,
        'endColumn' => 42,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Mockery',
        'declaringClassName' => 'Mockery\\LegacyMockInterface',
        'implementingClassName' => 'Mockery\\LegacyMockInterface',
        'currentClassName' => 'Mockery\\LegacyMockInterface',
        'aliasName' => NULL,
      ),
      'mockery_setCurrentOrder' => 
      array (
        'name' => 'mockery_setCurrentOrder',
        'parameters' => 
        array (
          'order' => 
          array (
            'name' => 'order',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 128,
            'endLine' => 128,
            'startColumn' => 45,
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
 * Set current ordered number
 *
 * @param int $order
 *
 * @return int
 */',
        'startLine' => 128,
        'endLine' => 128,
        'startColumn' => 5,
        'endColumn' => 52,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Mockery',
        'declaringClassName' => 'Mockery\\LegacyMockInterface',
        'implementingClassName' => 'Mockery\\LegacyMockInterface',
        'currentClassName' => 'Mockery\\LegacyMockInterface',
        'aliasName' => NULL,
      ),
      'mockery_setExpectationsFor' => 
      array (
        'name' => 'mockery_setExpectationsFor',
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
            'startLine' => 137,
            'endLine' => 137,
            'startColumn' => 48,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'director' => 
          array (
            'name' => 'director',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Mockery\\ExpectationDirector',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 137,
            'endLine' => 137,
            'startColumn' => 57,
            'endColumn' => 85,
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
 * Return the expectations director for the given method
 *
 * @param string $method
 *
 * @return null|ExpectationDirector
 */',
        'startLine' => 137,
        'endLine' => 137,
        'startColumn' => 5,
        'endColumn' => 87,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Mockery',
        'declaringClassName' => 'Mockery\\LegacyMockInterface',
        'implementingClassName' => 'Mockery\\LegacyMockInterface',
        'currentClassName' => 'Mockery\\LegacyMockInterface',
        'aliasName' => NULL,
      ),
      'mockery_setGroup' => 
      array (
        'name' => 'mockery_setGroup',
        'parameters' => 
        array (
          'group' => 
          array (
            'name' => 'group',
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
            'startColumn' => 38,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'order' => 
          array (
            'name' => 'order',
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
            'startColumn' => 46,
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
 * Set ordering for a group
 *
 * @param string $group
 * @param int    $order
 *
 * @return void
 */',
        'startLine' => 147,
        'endLine' => 147,
        'startColumn' => 5,
        'endColumn' => 53,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Mockery',
        'declaringClassName' => 'Mockery\\LegacyMockInterface',
        'implementingClassName' => 'Mockery\\LegacyMockInterface',
        'currentClassName' => 'Mockery\\LegacyMockInterface',
        'aliasName' => NULL,
      ),
      'mockery_teardown' => 
      array (
        'name' => 'mockery_teardown',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Tear down tasks for this mock
 *
 * @return void
 */',
        'startLine' => 154,
        'endLine' => 154,
        'startColumn' => 5,
        'endColumn' => 39,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Mockery',
        'declaringClassName' => 'Mockery\\LegacyMockInterface',
        'implementingClassName' => 'Mockery\\LegacyMockInterface',
        'currentClassName' => 'Mockery\\LegacyMockInterface',
        'aliasName' => NULL,
      ),
      'mockery_validateOrder' => 
      array (
        'name' => 'mockery_validateOrder',
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
            'startLine' => 166,
            'endLine' => 166,
            'startColumn' => 43,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'order' => 
          array (
            'name' => 'order',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 166,
            'endLine' => 166,
            'startColumn' => 52,
            'endColumn' => 57,
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
 * Validate the current mock\'s ordering
 *
 * @param string $method
 * @param int    $order
 *
 * @throws Exception
 *
 * @return void
 */',
        'startLine' => 166,
        'endLine' => 166,
        'startColumn' => 5,
        'endColumn' => 59,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Mockery',
        'declaringClassName' => 'Mockery\\LegacyMockInterface',
        'implementingClassName' => 'Mockery\\LegacyMockInterface',
        'currentClassName' => 'Mockery\\LegacyMockInterface',
        'aliasName' => NULL,
      ),
      'mockery_verify' => 
      array (
        'name' => 'mockery_verify',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Iterate across all expectation directors and validate each
 *
 * @throws Throwable
 *
 * @return void
 */',
        'startLine' => 175,
        'endLine' => 175,
        'startColumn' => 5,
        'endColumn' => 37,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Mockery',
        'declaringClassName' => 'Mockery\\LegacyMockInterface',
        'implementingClassName' => 'Mockery\\LegacyMockInterface',
        'currentClassName' => 'Mockery\\LegacyMockInterface',
        'aliasName' => NULL,
      ),
      'shouldAllowMockingMethod' => 
      array (
        'name' => 'shouldAllowMockingMethod',
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
            'startLine' => 183,
            'endLine' => 183,
            'startColumn' => 46,
            'endColumn' => 52,
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
 * Allows additional methods to be mocked that do not explicitly exist on mocked class
 *
 * @param  string $method the method name to be mocked
 * @return self
 */',
        'startLine' => 183,
        'endLine' => 183,
        'startColumn' => 5,
        'endColumn' => 54,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Mockery',
        'declaringClassName' => 'Mockery\\LegacyMockInterface',
        'implementingClassName' => 'Mockery\\LegacyMockInterface',
        'currentClassName' => 'Mockery\\LegacyMockInterface',
        'aliasName' => NULL,
      ),
      'shouldAllowMockingProtectedMethods' => 
      array (
        'name' => 'shouldAllowMockingProtectedMethods',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return self
 */',
        'startLine' => 188,
        'endLine' => 188,
        'startColumn' => 5,
        'endColumn' => 57,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Mockery',
        'declaringClassName' => 'Mockery\\LegacyMockInterface',
        'implementingClassName' => 'Mockery\\LegacyMockInterface',
        'currentClassName' => 'Mockery\\LegacyMockInterface',
        'aliasName' => NULL,
      ),
      'shouldDeferMissing' => 
      array (
        'name' => 'shouldDeferMissing',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Set mock to defer unexpected methods to its parent if possible
 *
 * @deprecated since 1.4.0. Please use makePartial() instead.
 *
 * @return self
 */',
        'startLine' => 197,
        'endLine' => 197,
        'startColumn' => 5,
        'endColumn' => 41,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Mockery',
        'declaringClassName' => 'Mockery\\LegacyMockInterface',
        'implementingClassName' => 'Mockery\\LegacyMockInterface',
        'currentClassName' => 'Mockery\\LegacyMockInterface',
        'aliasName' => NULL,
      ),
      'shouldHaveBeenCalled' => 
      array (
        'name' => 'shouldHaveBeenCalled',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return self
 */',
        'startLine' => 202,
        'endLine' => 202,
        'startColumn' => 5,
        'endColumn' => 43,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Mockery',
        'declaringClassName' => 'Mockery\\LegacyMockInterface',
        'implementingClassName' => 'Mockery\\LegacyMockInterface',
        'currentClassName' => 'Mockery\\LegacyMockInterface',
        'aliasName' => NULL,
      ),
      'shouldHaveReceived' => 
      array (
        'name' => 'shouldHaveReceived',
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
            'startLine' => 211,
            'endLine' => 211,
            'startColumn' => 40,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'args' => 
          array (
            'name' => 'args',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 211,
                'endLine' => 211,
                'startTokenPos' => 342,
                'startFilePos' => 4595,
                'endTokenPos' => 342,
                'endFilePos' => 4598,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 211,
            'endLine' => 211,
            'startColumn' => 49,
            'endColumn' => 60,
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
 * @template TMixed
 * @param string                     $method
 * @param null|array<TMixed>|Closure $args
 *
 * @return self
 */',
        'startLine' => 211,
        'endLine' => 211,
        'startColumn' => 5,
        'endColumn' => 62,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Mockery',
        'declaringClassName' => 'Mockery\\LegacyMockInterface',
        'implementingClassName' => 'Mockery\\LegacyMockInterface',
        'currentClassName' => 'Mockery\\LegacyMockInterface',
        'aliasName' => NULL,
      ),
      'shouldIgnoreMissing' => 
      array (
        'name' => 'shouldIgnoreMissing',
        'parameters' => 
        array (
          'returnValue' => 
          array (
            'name' => 'returnValue',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 222,
                'endLine' => 222,
                'startTokenPos' => 358,
                'startFilePos' => 4931,
                'endTokenPos' => 358,
                'endFilePos' => 4934,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 222,
            'endLine' => 222,
            'startColumn' => 41,
            'endColumn' => 59,
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
 * Set mock to ignore unexpected methods and return Undefined class
 *
 * @template TReturnValue
 *
 * @param null|TReturnValue $returnValue the default return value for calls to missing functions on this mock
 *
 * @return self
 */',
        'startLine' => 222,
        'endLine' => 222,
        'startColumn' => 5,
        'endColumn' => 61,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Mockery',
        'declaringClassName' => 'Mockery\\LegacyMockInterface',
        'implementingClassName' => 'Mockery\\LegacyMockInterface',
        'currentClassName' => 'Mockery\\LegacyMockInterface',
        'aliasName' => NULL,
      ),
      'shouldNotHaveBeenCalled' => 
      array (
        'name' => 'shouldNotHaveBeenCalled',
        'parameters' => 
        array (
          'args' => 
          array (
            'name' => 'args',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 230,
                'endLine' => 230,
                'startTokenPos' => 377,
                'startFilePos' => 5115,
                'endTokenPos' => 377,
                'endFilePos' => 5118,
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
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 230,
            'endLine' => 230,
            'startColumn' => 45,
            'endColumn' => 63,
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
 * @template TMixed
 * @param null|array<TMixed> $args (optional)
 *
 * @return self
 */',
        'startLine' => 230,
        'endLine' => 230,
        'startColumn' => 5,
        'endColumn' => 65,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Mockery',
        'declaringClassName' => 'Mockery\\LegacyMockInterface',
        'implementingClassName' => 'Mockery\\LegacyMockInterface',
        'currentClassName' => 'Mockery\\LegacyMockInterface',
        'aliasName' => NULL,
      ),
      'shouldNotHaveReceived' => 
      array (
        'name' => 'shouldNotHaveReceived',
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
            'startLine' => 239,
            'endLine' => 239,
            'startColumn' => 43,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'args' => 
          array (
            'name' => 'args',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 239,
                'endLine' => 239,
                'startTokenPos' => 396,
                'startFilePos' => 5345,
                'endTokenPos' => 396,
                'endFilePos' => 5348,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 239,
            'endLine' => 239,
            'startColumn' => 52,
            'endColumn' => 63,
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
 * @template TMixed
 * @param string                     $method
 * @param null|array<TMixed>|Closure $args
 *
 * @return self
 */',
        'startLine' => 239,
        'endLine' => 239,
        'startColumn' => 5,
        'endColumn' => 65,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Mockery',
        'declaringClassName' => 'Mockery\\LegacyMockInterface',
        'implementingClassName' => 'Mockery\\LegacyMockInterface',
        'currentClassName' => 'Mockery\\LegacyMockInterface',
        'aliasName' => NULL,
      ),
      'shouldNotReceive' => 
      array (
        'name' => 'shouldNotReceive',
        'parameters' => 
        array (
          'methodNames' => 
          array (
            'name' => 'methodNames',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => true,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 248,
            'endLine' => 248,
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
 * Shortcut method for setting an expectation that a method should not be called.
 *
 * @param string ...$methodNames one or many methods that are expected not to be called in this mock
 *
 * @return Expectation|ExpectationInterface|HigherOrderMessage
 */',
        'startLine' => 248,
        'endLine' => 248,
        'startColumn' => 5,
        'endColumn' => 54,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Mockery',
        'declaringClassName' => 'Mockery\\LegacyMockInterface',
        'implementingClassName' => 'Mockery\\LegacyMockInterface',
        'currentClassName' => 'Mockery\\LegacyMockInterface',
        'aliasName' => NULL,
      ),
      'shouldReceive' => 
      array (
        'name' => 'shouldReceive',
        'parameters' => 
        array (
          'methodNames' => 
          array (
            'name' => 'methodNames',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => true,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 257,
            'endLine' => 257,
            'startColumn' => 35,
            'endColumn' => 49,
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
 * Set expected method calls
 *
 * @param string ...$methodNames one or many methods that are expected to be called in this mock
 *
 * @return Expectation|ExpectationInterface|HigherOrderMessage
 */',
        'startLine' => 257,
        'endLine' => 257,
        'startColumn' => 5,
        'endColumn' => 51,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Mockery',
        'declaringClassName' => 'Mockery\\LegacyMockInterface',
        'implementingClassName' => 'Mockery\\LegacyMockInterface',
        'currentClassName' => 'Mockery\\LegacyMockInterface',
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