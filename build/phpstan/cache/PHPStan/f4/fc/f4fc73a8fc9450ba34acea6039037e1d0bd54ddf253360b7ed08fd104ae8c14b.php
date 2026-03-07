<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../filament/filament/src/Pages/Dashboard/Concerns/HasFilters.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Filament\Pages\Dashboard\Concerns\HasFilters
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-19e3ae90ed71fb981594c3ab1f8d161a41ed9e88bef25241d80593de0eb07f09-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Filament\\Pages\\Dashboard\\Concerns\\HasFilters',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../filament/filament/src/Pages/Dashboard/Concerns/HasFilters.php',
      ),
    ),
    'namespace' => 'Filament\\Pages\\Dashboard\\Concerns',
    'name' => 'Filament\\Pages\\Dashboard\\Concerns\\HasFilters',
    'shortName' => 'HasFilters',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 7,
    'endLine' => 90,
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
      'filters' => 
      array (
        'declaringClassName' => 'Filament\\Pages\\Dashboard\\Concerns\\HasFilters',
        'implementingClassName' => 'Filament\\Pages\\Dashboard\\Concerns\\HasFilters',
        'name' => 'filters',
        'modifiers' => 1,
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
            'startLine' => 13,
            'endLine' => 13,
            'startTokenPos' => 35,
            'startFilePos' => 234,
            'endTokenPos' => 35,
            'endFilePos' => 237,
          ),
        ),
        'docComment' => '/**
 * @var array<string, mixed> | null
 */',
        'attributes' => 
        array (
          0 => 
          array (
            'name' => 'Livewire\\Attributes\\Url',
            'isRepeated' => false,
            'arguments' => 
            array (
            ),
          ),
        ),
        'startLine' => 12,
        'endLine' => 13,
        'startColumn' => 5,
        'endColumn' => 34,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'persistsFiltersInSession' => 
      array (
        'declaringClassName' => 'Filament\\Pages\\Dashboard\\Concerns\\HasFilters',
        'implementingClassName' => 'Filament\\Pages\\Dashboard\\Concerns\\HasFilters',
        'name' => 'persistsFiltersInSession',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => 'true',
          'attributes' => 
          array (
            'startLine' => 15,
            'endLine' => 15,
            'startTokenPos' => 46,
            'startFilePos' => 288,
            'endTokenPos' => 46,
            'endFilePos' => 291,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 15,
        'endLine' => 15,
        'startColumn' => 5,
        'endColumn' => 52,
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
      'mountHasFilters' => 
      array (
        'name' => 'mountHasFilters',
        'parameters' => 
        array (
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
        'docComment' => NULL,
        'startLine' => 17,
        'endLine' => 49,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Filament\\Pages\\Dashboard\\Concerns',
        'declaringClassName' => 'Filament\\Pages\\Dashboard\\Concerns\\HasFilters',
        'implementingClassName' => 'Filament\\Pages\\Dashboard\\Concerns\\HasFilters',
        'currentClassName' => 'Filament\\Pages\\Dashboard\\Concerns\\HasFilters',
        'aliasName' => NULL,
      ),
      'normalizeTableFilterValuesFromQueryString' => 
      array (
        'name' => 'normalizeTableFilterValuesFromQueryString',
        'parameters' => 
        array (
          'data' => 
          array (
            'name' => 'data',
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
            'byRef' => true,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 54,
            'endLine' => 54,
            'startColumn' => 66,
            'endColumn' => 77,
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
 * @param  array<string, mixed>  $data
 */',
        'startLine' => 54,
        'endLine' => 67,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Filament\\Pages\\Dashboard\\Concerns',
        'declaringClassName' => 'Filament\\Pages\\Dashboard\\Concerns\\HasFilters',
        'implementingClassName' => 'Filament\\Pages\\Dashboard\\Concerns\\HasFilters',
        'currentClassName' => 'Filament\\Pages\\Dashboard\\Concerns\\HasFilters',
        'aliasName' => NULL,
      ),
      'updatedFilters' => 
      array (
        'name' => 'updatedFilters',
        'parameters' => 
        array (
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
        'docComment' => NULL,
        'startLine' => 69,
        'endLine' => 77,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Filament\\Pages\\Dashboard\\Concerns',
        'declaringClassName' => 'Filament\\Pages\\Dashboard\\Concerns\\HasFilters',
        'implementingClassName' => 'Filament\\Pages\\Dashboard\\Concerns\\HasFilters',
        'currentClassName' => 'Filament\\Pages\\Dashboard\\Concerns\\HasFilters',
        'aliasName' => NULL,
      ),
      'getFiltersSessionKey' => 
      array (
        'name' => 'getFiltersSessionKey',
        'parameters' => 
        array (
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
        'docComment' => NULL,
        'startLine' => 79,
        'endLine' => 84,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Filament\\Pages\\Dashboard\\Concerns',
        'declaringClassName' => 'Filament\\Pages\\Dashboard\\Concerns\\HasFilters',
        'implementingClassName' => 'Filament\\Pages\\Dashboard\\Concerns\\HasFilters',
        'currentClassName' => 'Filament\\Pages\\Dashboard\\Concerns\\HasFilters',
        'aliasName' => NULL,
      ),
      'persistsFiltersInSession' => 
      array (
        'name' => 'persistsFiltersInSession',
        'parameters' => 
        array (
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
        'docComment' => NULL,
        'startLine' => 86,
        'endLine' => 89,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Filament\\Pages\\Dashboard\\Concerns',
        'declaringClassName' => 'Filament\\Pages\\Dashboard\\Concerns\\HasFilters',
        'implementingClassName' => 'Filament\\Pages\\Dashboard\\Concerns\\HasFilters',
        'currentClassName' => 'Filament\\Pages\\Dashboard\\Concerns\\HasFilters',
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