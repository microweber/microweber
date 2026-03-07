<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Database/Eloquent/Relations/Concerns/SupportsDefaultModels.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Database\Eloquent\Relations\Concerns\SupportsDefaultModels
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-1e6a9ceb6e4a621e3292d7b027f4949182c1649b54a5beb20aae7c23feb069c9-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\SupportsDefaultModels',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Database/Eloquent/Relations/Concerns/SupportsDefaultModels.php',
      ),
    ),
    'namespace' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns',
    'name' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\SupportsDefaultModels',
    'shortName' => 'SupportsDefaultModels',
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
    'endLine' => 63,
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
      'withDefault' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\SupportsDefaultModels',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\SupportsDefaultModels',
        'name' => 'withDefault',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * Indicates if a default model instance should be used.
 *
 * Alternatively, may be a Closure or array.
 *
 * @var \\Closure|array|bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 16,
        'endLine' => 16,
        'startColumn' => 5,
        'endColumn' => 27,
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
      'newRelatedInstanceFor' => 
      array (
        'name' => 'newRelatedInstanceFor',
        'parameters' => 
        array (
          'parent' => 
          array (
            'name' => 'parent',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Model',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 24,
            'endLine' => 24,
            'startColumn' => 55,
            'endColumn' => 67,
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
 * Make a new related instance for the given model.
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Model  $parent
 * @return \\Illuminate\\Database\\Eloquent\\Model
 */',
        'startLine' => 24,
        'endLine' => 24,
        'startColumn' => 5,
        'endColumn' => 69,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 66,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\SupportsDefaultModels',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\SupportsDefaultModels',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\SupportsDefaultModels',
        'aliasName' => NULL,
      ),
      'withDefault' => 
      array (
        'name' => 'withDefault',
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
                'startLine' => 32,
                'endLine' => 32,
                'startTokenPos' => 53,
                'startFilePos' => 810,
                'endTokenPos' => 53,
                'endFilePos' => 813,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 32,
            'endLine' => 32,
            'startColumn' => 33,
            'endColumn' => 48,
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
 * Return a new model instance in case the relationship does not exist.
 *
 * @param  \\Closure|array|bool  $callback
 * @return $this
 */',
        'startLine' => 32,
        'endLine' => 37,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\SupportsDefaultModels',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\SupportsDefaultModels',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\SupportsDefaultModels',
        'aliasName' => NULL,
      ),
      'getDefaultFor' => 
      array (
        'name' => 'getDefaultFor',
        'parameters' => 
        array (
          'parent' => 
          array (
            'name' => 'parent',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Model',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 45,
            'endLine' => 45,
            'startColumn' => 38,
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
 * Get the default value for this relation.
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Model  $parent
 * @return \\Illuminate\\Database\\Eloquent\\Model|null
 */',
        'startLine' => 45,
        'endLine' => 62,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\SupportsDefaultModels',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\SupportsDefaultModels',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Relations\\Concerns\\SupportsDefaultModels',
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