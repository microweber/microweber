<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../psr/container/src/ContainerInterface.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Psr\Container\ContainerInterface
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-8c8f8d1a3dececb5f09514a35f7ad01afa5674faa2d08834bcbe5921a9f2e35b-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Psr\\Container\\ContainerInterface',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../psr/container/src/ContainerInterface.php',
      ),
    ),
    'namespace' => 'Psr\\Container',
    'name' => 'Psr\\Container\\ContainerInterface',
    'shortName' => 'ContainerInterface',
    'isInterface' => true,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Describes the interface of a container that exposes methods to read its entries.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 10,
    'endLine' => 36,
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
      'get' => 
      array (
        'name' => 'get',
        'parameters' => 
        array (
          'id' => 
          array (
            'name' => 'id',
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
            'startLine' => 22,
            'endLine' => 22,
            'startColumn' => 25,
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
 * Finds an entry of the container by its identifier and returns it.
 *
 * @param string $id Identifier of the entry to look for.
 *
 * @throws NotFoundExceptionInterface  No entry was found for **this** identifier.
 * @throws ContainerExceptionInterface Error while retrieving the entry.
 *
 * @return mixed Entry.
 */',
        'startLine' => 22,
        'endLine' => 22,
        'startColumn' => 5,
        'endColumn' => 36,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Psr\\Container',
        'declaringClassName' => 'Psr\\Container\\ContainerInterface',
        'implementingClassName' => 'Psr\\Container\\ContainerInterface',
        'currentClassName' => 'Psr\\Container\\ContainerInterface',
        'aliasName' => NULL,
      ),
      'has' => 
      array (
        'name' => 'has',
        'parameters' => 
        array (
          'id' => 
          array (
            'name' => 'id',
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
            'startLine' => 35,
            'endLine' => 35,
            'startColumn' => 25,
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
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns true if the container can return an entry for the given identifier.
 * Returns false otherwise.
 *
 * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
 * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
 *
 * @param string $id Identifier of the entry to look for.
 *
 * @return bool
 */',
        'startLine' => 35,
        'endLine' => 35,
        'startColumn' => 5,
        'endColumn' => 42,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Psr\\Container',
        'declaringClassName' => 'Psr\\Container\\ContainerInterface',
        'implementingClassName' => 'Psr\\Container\\ContainerInterface',
        'currentClassName' => 'Psr\\Container\\ContainerInterface',
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