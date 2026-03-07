<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Database/Eloquent/SoftDeletes.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Database\Eloquent\SoftDeletes
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-088774910033ee16d93400eb71c4befe82edb195c45e9cb4579093e91e9fe2df-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Database/Eloquent/SoftDeletes.php',
      ),
    ),
    'namespace' => 'Illuminate\\Database\\Eloquent',
    'name' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
    'shortName' => 'SoftDeletes',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @method static \\Illuminate\\Database\\Eloquent\\Builder<static> withTrashed(bool $withTrashed = true)
 * @method static \\Illuminate\\Database\\Eloquent\\Builder<static> onlyTrashed()
 * @method static \\Illuminate\\Database\\Eloquent\\Builder<static> withoutTrashed()
 * @method static static restoreOrCreate(array<string, mixed> $attributes = [], array<string, mixed> $values = [])
 * @method static static createOrRestore(array<string, mixed> $attributes = [], array<string, mixed> $values = [])
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 15,
    'endLine' => 292,
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
      'forceDeleting' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'name' => 'forceDeleting',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 22,
            'endLine' => 22,
            'startTokenPos' => 41,
            'startFilePos' => 818,
            'endTokenPos' => 41,
            'endFilePos' => 822,
          ),
        ),
        'docComment' => '/**
 * Indicates if the model is currently force deleting.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 22,
        'endLine' => 22,
        'startColumn' => 5,
        'endColumn' => 37,
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
      'bootSoftDeletes' => 
      array (
        'name' => 'bootSoftDeletes',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Boot the soft deleting trait for a model.
 *
 * @return void
 */',
        'startLine' => 29,
        'endLine' => 32,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'aliasName' => NULL,
      ),
      'initializeSoftDeletes' => 
      array (
        'name' => 'initializeSoftDeletes',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Initialize the soft deleting trait for an instance.
 *
 * @return void
 */',
        'startLine' => 39,
        'endLine' => 44,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'aliasName' => NULL,
      ),
      'forceDelete' => 
      array (
        'name' => 'forceDelete',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Force a hard delete on a soft deleted model.
 *
 * @return bool|null
 */',
        'startLine' => 51,
        'endLine' => 66,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'aliasName' => NULL,
      ),
      'forceDeleteQuietly' => 
      array (
        'name' => 'forceDeleteQuietly',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Force a hard delete on a soft deleted model without raising any events.
 *
 * @return bool|null
 */',
        'startLine' => 73,
        'endLine' => 76,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'aliasName' => NULL,
      ),
      'forceDestroy' => 
      array (
        'name' => 'forceDestroy',
        'parameters' => 
        array (
          'ids' => 
          array (
            'name' => 'ids',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 84,
            'endLine' => 84,
            'startColumn' => 41,
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
 * Destroy the models for the given IDs.
 *
 * @param  \\Illuminate\\Support\\Collection|array|int|string  $ids
 * @return int
 */',
        'startLine' => 84,
        'endLine' => 114,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'aliasName' => NULL,
      ),
      'performDeleteOnModel' => 
      array (
        'name' => 'performDeleteOnModel',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Perform the actual delete query on this model instance.
 *
 * @return mixed
 */',
        'startLine' => 121,
        'endLine' => 130,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'aliasName' => NULL,
      ),
      'runSoftDelete' => 
      array (
        'name' => 'runSoftDelete',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Perform the actual delete query on this model instance.
 *
 * @return void
 */',
        'startLine' => 137,
        'endLine' => 158,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'aliasName' => NULL,
      ),
      'restore' => 
      array (
        'name' => 'restore',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Restore a soft-deleted model instance.
 *
 * @return bool
 */',
        'startLine' => 165,
        'endLine' => 186,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'aliasName' => NULL,
      ),
      'restoreQuietly' => 
      array (
        'name' => 'restoreQuietly',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Restore a soft-deleted model instance without raising any events.
 *
 * @return bool
 */',
        'startLine' => 193,
        'endLine' => 196,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'aliasName' => NULL,
      ),
      'trashed' => 
      array (
        'name' => 'trashed',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the model instance has been soft-deleted.
 *
 * @return bool
 */',
        'startLine' => 203,
        'endLine' => 206,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'aliasName' => NULL,
      ),
      'softDeleted' => 
      array (
        'name' => 'softDeleted',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 214,
            'endLine' => 214,
            'startColumn' => 40,
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
 * Register a "softDeleted" model event callback with the dispatcher.
 *
 * @param  \\Illuminate\\Events\\QueuedClosure|callable|class-string  $callback
 * @return void
 */',
        'startLine' => 214,
        'endLine' => 217,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'aliasName' => NULL,
      ),
      'restoring' => 
      array (
        'name' => 'restoring',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 225,
            'endLine' => 225,
            'startColumn' => 38,
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
 * Register a "restoring" model event callback with the dispatcher.
 *
 * @param  \\Illuminate\\Events\\QueuedClosure|callable|class-string  $callback
 * @return void
 */',
        'startLine' => 225,
        'endLine' => 228,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'aliasName' => NULL,
      ),
      'restored' => 
      array (
        'name' => 'restored',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 236,
            'endLine' => 236,
            'startColumn' => 37,
            'endColumn' => 45,
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
 * Register a "restored" model event callback with the dispatcher.
 *
 * @param  \\Illuminate\\Events\\QueuedClosure|callable|class-string  $callback
 * @return void
 */',
        'startLine' => 236,
        'endLine' => 239,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'aliasName' => NULL,
      ),
      'forceDeleting' => 
      array (
        'name' => 'forceDeleting',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 247,
            'endLine' => 247,
            'startColumn' => 42,
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
 * Register a "forceDeleting" model event callback with the dispatcher.
 *
 * @param  \\Illuminate\\Events\\QueuedClosure|callable|class-string  $callback
 * @return void
 */',
        'startLine' => 247,
        'endLine' => 250,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'aliasName' => NULL,
      ),
      'forceDeleted' => 
      array (
        'name' => 'forceDeleted',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 258,
            'endLine' => 258,
            'startColumn' => 41,
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
 * Register a "forceDeleted" model event callback with the dispatcher.
 *
 * @param  \\Illuminate\\Events\\QueuedClosure|callable|class-string  $callback
 * @return void
 */',
        'startLine' => 258,
        'endLine' => 261,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'aliasName' => NULL,
      ),
      'isForceDeleting' => 
      array (
        'name' => 'isForceDeleting',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the model is currently force deleting.
 *
 * @return bool
 */',
        'startLine' => 268,
        'endLine' => 271,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'aliasName' => NULL,
      ),
      'getDeletedAtColumn' => 
      array (
        'name' => 'getDeletedAtColumn',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the name of the "deleted at" column.
 *
 * @return string
 */',
        'startLine' => 278,
        'endLine' => 281,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'aliasName' => NULL,
      ),
      'getQualifiedDeletedAtColumn' => 
      array (
        'name' => 'getQualifiedDeletedAtColumn',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the fully qualified "deleted at" column.
 *
 * @return string
 */',
        'startLine' => 288,
        'endLine' => 291,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
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