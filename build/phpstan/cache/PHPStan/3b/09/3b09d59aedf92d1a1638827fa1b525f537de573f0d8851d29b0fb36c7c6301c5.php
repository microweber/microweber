<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Database/Eloquent/Model.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Database\Eloquent\Model
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-c13a08536e21010fe5b251a001a9d4708c479ced25f92025fad14fe54eacc706-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Database\\Eloquent\\Model',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Database/Eloquent/Model.php',
      ),
    ),
    'namespace' => 'Illuminate\\Database\\Eloquent',
    'name' => 'Illuminate\\Database\\Eloquent\\Model',
    'shortName' => 'Model',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 64,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 29,
    'endLine' => 2438,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'Illuminate\\Contracts\\Support\\Arrayable',
      1 => 'ArrayAccess',
      2 => 'Illuminate\\Contracts\\Support\\CanBeEscapedWhenCastToString',
      3 => 'Illuminate\\Contracts\\Broadcasting\\HasBroadcastChannel',
      4 => 'Illuminate\\Contracts\\Support\\Jsonable',
      5 => 'JsonSerializable',
      6 => 'Illuminate\\Contracts\\Queue\\QueueableEntity',
      7 => 'Stringable',
      8 => 'Illuminate\\Contracts\\Routing\\UrlRoutable',
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasAttributes',
      1 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasEvents',
      2 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasGlobalScopes',
      3 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasRelationships',
      4 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasTimestamps',
      5 => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUniqueIds',
      6 => 'Illuminate\\Database\\Eloquent\\Concerns\\HidesAttributes',
      7 => 'Illuminate\\Database\\Eloquent\\Concerns\\GuardsAttributes',
      8 => 'Illuminate\\Database\\Eloquent\\Concerns\\PreventsCircularRecursion',
      9 => 'Illuminate\\Support\\Traits\\ForwardsCalls',
      10 => 'Illuminate\\Database\\Eloquent\\HasCollection',
    ),
    'immediateConstants' => 
    array (
      'CREATED_AT' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'name' => 'CREATED_AT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'created_at\'',
          'attributes' => 
          array (
            'startLine' => 238,
            'endLine' => 238,
            'startTokenPos' => 534,
            'startFilePos' => 6047,
            'endTokenPos' => 534,
            'endFilePos' => 6058,
          ),
        ),
        'docComment' => '/**
 * The name of the "created at" column.
 *
 * @var string|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 238,
        'endLine' => 238,
        'startColumn' => 5,
        'endColumn' => 36,
      ),
      'UPDATED_AT' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'name' => 'UPDATED_AT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'updated_at\'',
          'attributes' => 
          array (
            'startLine' => 245,
            'endLine' => 245,
            'startTokenPos' => 545,
            'startFilePos' => 6176,
            'endTokenPos' => 545,
            'endFilePos' => 6187,
          ),
        ),
        'docComment' => '/**
 * The name of the "updated at" column.
 *
 * @var string|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 245,
        'endLine' => 245,
        'startColumn' => 5,
        'endColumn' => 36,
      ),
    ),
    'immediateProperties' => 
    array (
      'connection' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'name' => 'connection',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The connection name for the model.
 *
 * @var string|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 49,
        'endLine' => 49,
        'startColumn' => 5,
        'endColumn' => 26,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'table' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The table associated with the model.
 *
 * @var string|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 56,
        'endLine' => 56,
        'startColumn' => 5,
        'endColumn' => 21,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'primaryKey' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'name' => 'primaryKey',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'id\'',
          'attributes' => 
          array (
            'startLine' => 63,
            'endLine' => 63,
            'startTokenPos' => 235,
            'startFilePos' => 2035,
            'endTokenPos' => 235,
            'endFilePos' => 2038,
          ),
        ),
        'docComment' => '/**
 * The primary key for the model.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 63,
        'endLine' => 63,
        'startColumn' => 5,
        'endColumn' => 33,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'keyType' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'name' => 'keyType',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'int\'',
          'attributes' => 
          array (
            'startLine' => 70,
            'endLine' => 70,
            'startTokenPos' => 246,
            'startFilePos' => 2150,
            'endTokenPos' => 246,
            'endFilePos' => 2154,
          ),
        ),
        'docComment' => '/**
 * The "type" of the primary key ID.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 70,
        'endLine' => 70,
        'startColumn' => 5,
        'endColumn' => 31,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'incrementing' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'name' => 'incrementing',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'true',
          'attributes' => 
          array (
            'startLine' => 77,
            'endLine' => 77,
            'startTokenPos' => 257,
            'startFilePos' => 2276,
            'endTokenPos' => 257,
            'endFilePos' => 2279,
          ),
        ),
        'docComment' => '/**
 * Indicates if the IDs are auto-incrementing.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 77,
        'endLine' => 77,
        'startColumn' => 5,
        'endColumn' => 32,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'with' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'name' => 'with',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 84,
            'endLine' => 84,
            'startTokenPos' => 268,
            'startFilePos' => 2397,
            'endTokenPos' => 269,
            'endFilePos' => 2398,
          ),
        ),
        'docComment' => '/**
 * The relations to eager load on every query.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 84,
        'endLine' => 84,
        'startColumn' => 5,
        'endColumn' => 25,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'withCount' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'name' => 'withCount',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 91,
            'endLine' => 91,
            'startTokenPos' => 280,
            'startFilePos' => 2545,
            'endTokenPos' => 281,
            'endFilePos' => 2546,
          ),
        ),
        'docComment' => '/**
 * The relationship counts that should be eager loaded on every query.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 91,
        'endLine' => 91,
        'startColumn' => 5,
        'endColumn' => 30,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'preventsLazyLoading' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'name' => 'preventsLazyLoading',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 98,
            'endLine' => 98,
            'startTokenPos' => 292,
            'startFilePos' => 2695,
            'endTokenPos' => 292,
            'endFilePos' => 2699,
          ),
        ),
        'docComment' => '/**
 * Indicates whether lazy loading will be prevented on this model.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 98,
        'endLine' => 98,
        'startColumn' => 5,
        'endColumn' => 40,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'perPage' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'name' => 'perPage',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '15',
          'attributes' => 
          array (
            'startLine' => 105,
            'endLine' => 105,
            'startTokenPos' => 303,
            'startFilePos' => 2821,
            'endTokenPos' => 303,
            'endFilePos' => 2822,
          ),
        ),
        'docComment' => '/**
 * The number of models to return for pagination.
 *
 * @var int
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 105,
        'endLine' => 105,
        'startColumn' => 5,
        'endColumn' => 28,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'exists' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'name' => 'exists',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 112,
            'endLine' => 112,
            'startTokenPos' => 314,
            'startFilePos' => 2925,
            'endTokenPos' => 314,
            'endFilePos' => 2929,
          ),
        ),
        'docComment' => '/**
 * Indicates if the model exists.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 112,
        'endLine' => 112,
        'startColumn' => 5,
        'endColumn' => 27,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'wasRecentlyCreated' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'name' => 'wasRecentlyCreated',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 119,
            'endLine' => 119,
            'startTokenPos' => 325,
            'startFilePos' => 3080,
            'endTokenPos' => 325,
            'endFilePos' => 3084,
          ),
        ),
        'docComment' => '/**
 * Indicates if the model was inserted during the object\'s lifecycle.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 119,
        'endLine' => 119,
        'startColumn' => 5,
        'endColumn' => 39,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'escapeWhenCastingToString' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'name' => 'escapeWhenCastingToString',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 126,
            'endLine' => 126,
            'startTokenPos' => 336,
            'startFilePos' => 3274,
            'endTokenPos' => 336,
            'endFilePos' => 3278,
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
        'startLine' => 126,
        'endLine' => 126,
        'startColumn' => 5,
        'endColumn' => 49,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'resolver' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'name' => 'resolver',
        'modifiers' => 18,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The connection resolver instance.
 *
 * @var \\Illuminate\\Database\\ConnectionResolverInterface
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 133,
        'endLine' => 133,
        'startColumn' => 5,
        'endColumn' => 31,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'dispatcher' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'name' => 'dispatcher',
        'modifiers' => 18,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The event dispatcher instance.
 *
 * @var \\Illuminate\\Contracts\\Events\\Dispatcher|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 140,
        'endLine' => 140,
        'startColumn' => 5,
        'endColumn' => 33,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'booted' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'name' => 'booted',
        'modifiers' => 18,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 147,
            'endLine' => 147,
            'startTokenPos' => 367,
            'startFilePos' => 3700,
            'endTokenPos' => 368,
            'endFilePos' => 3701,
          ),
        ),
        'docComment' => '/**
 * The array of booted models.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 147,
        'endLine' => 147,
        'startColumn' => 5,
        'endColumn' => 34,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'traitInitializers' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'name' => 'traitInitializers',
        'modifiers' => 18,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 154,
            'endLine' => 154,
            'startTokenPos' => 381,
            'startFilePos' => 3869,
            'endTokenPos' => 382,
            'endFilePos' => 3870,
          ),
        ),
        'docComment' => '/**
 * The array of trait initializers that will be called on each new instance.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 154,
        'endLine' => 154,
        'startColumn' => 5,
        'endColumn' => 45,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'globalScopes' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'name' => 'globalScopes',
        'modifiers' => 18,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 161,
            'endLine' => 161,
            'startTokenPos' => 395,
            'startFilePos' => 4000,
            'endTokenPos' => 396,
            'endFilePos' => 4001,
          ),
        ),
        'docComment' => '/**
 * The array of global scopes on the model.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 161,
        'endLine' => 161,
        'startColumn' => 5,
        'endColumn' => 40,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'ignoreOnTouch' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'name' => 'ignoreOnTouch',
        'modifiers' => 18,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 168,
            'endLine' => 168,
            'startTokenPos' => 409,
            'startFilePos' => 4158,
            'endTokenPos' => 410,
            'endFilePos' => 4159,
          ),
        ),
        'docComment' => '/**
 * The list of models classes that should not be affected with touch.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 168,
        'endLine' => 168,
        'startColumn' => 5,
        'endColumn' => 41,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'modelsShouldPreventLazyLoading' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'name' => 'modelsShouldPreventLazyLoading',
        'modifiers' => 18,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 175,
            'endLine' => 175,
            'startTokenPos' => 423,
            'startFilePos' => 4332,
            'endTokenPos' => 423,
            'endFilePos' => 4336,
          ),
        ),
        'docComment' => '/**
 * Indicates whether lazy loading should be restricted on all models.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 175,
        'endLine' => 175,
        'startColumn' => 5,
        'endColumn' => 61,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'lazyLoadingViolationCallback' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'name' => 'lazyLoadingViolationCallback',
        'modifiers' => 18,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The callback that is responsible for handling lazy loading violations.
 *
 * @var callable|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 182,
        'endLine' => 182,
        'startColumn' => 5,
        'endColumn' => 51,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'modelsShouldPreventSilentlyDiscardingAttributes' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'name' => 'modelsShouldPreventSilentlyDiscardingAttributes',
        'modifiers' => 18,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 189,
            'endLine' => 189,
            'startTokenPos' => 445,
            'startFilePos' => 4738,
            'endTokenPos' => 445,
            'endFilePos' => 4742,
          ),
        ),
        'docComment' => '/**
 * Indicates if an exception should be thrown instead of silently discarding non-fillable attributes.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 189,
        'endLine' => 189,
        'startColumn' => 5,
        'endColumn' => 78,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'discardedAttributeViolationCallback' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'name' => 'discardedAttributeViolationCallback',
        'modifiers' => 18,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The callback that is responsible for handling discarded attribute violations.
 *
 * @var callable|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 196,
        'endLine' => 196,
        'startColumn' => 5,
        'endColumn' => 58,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'modelsShouldPreventAccessingMissingAttributes' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'name' => 'modelsShouldPreventAccessingMissingAttributes',
        'modifiers' => 18,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 203,
            'endLine' => 203,
            'startTokenPos' => 467,
            'startFilePos' => 5164,
            'endTokenPos' => 467,
            'endFilePos' => 5168,
          ),
        ),
        'docComment' => '/**
 * Indicates if an exception should be thrown when trying to access a missing attribute on a retrieved model.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 203,
        'endLine' => 203,
        'startColumn' => 5,
        'endColumn' => 76,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'missingAttributeViolationCallback' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'name' => 'missingAttributeViolationCallback',
        'modifiers' => 18,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The callback that is responsible for handling missing attribute violations.
 *
 * @var callable|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 210,
        'endLine' => 210,
        'startColumn' => 5,
        'endColumn' => 56,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'isBroadcasting' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'name' => 'isBroadcasting',
        'modifiers' => 18,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'true',
          'attributes' => 
          array (
            'startLine' => 217,
            'endLine' => 217,
            'startTokenPos' => 489,
            'startFilePos' => 5496,
            'endTokenPos' => 489,
            'endFilePos' => 5499,
          ),
        ),
        'docComment' => '/**
 * Indicates if broadcasting is currently enabled.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 217,
        'endLine' => 217,
        'startColumn' => 5,
        'endColumn' => 44,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'builder' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'name' => 'builder',
        'modifiers' => 18,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => '\\Illuminate\\Database\\Eloquent\\Builder::class',
          'attributes' => 
          array (
            'startLine' => 224,
            'endLine' => 224,
            'startTokenPos' => 504,
            'startFilePos' => 5694,
            'endTokenPos' => 506,
            'endFilePos' => 5707,
          ),
        ),
        'docComment' => '/**
 * The Eloquent query builder class to use for the model.
 *
 * @var class-string<\\Illuminate\\Database\\Eloquent\\Builder<*>>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 224,
        'endLine' => 224,
        'startColumn' => 5,
        'endColumn' => 54,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'collectionClass' => 
      array (
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'name' => 'collectionClass',
        'modifiers' => 18,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => '\\Illuminate\\Database\\Eloquent\\Collection::class',
          'attributes' => 
          array (
            'startLine' => 231,
            'endLine' => 231,
            'startTokenPos' => 521,
            'startFilePos' => 5913,
            'endTokenPos' => 523,
            'endFilePos' => 5929,
          ),
        ),
        'docComment' => '/**
 * The Eloquent collection class to use for the model.
 *
 * @var class-string<\\Illuminate\\Database\\Eloquent\\Collection<*, *>>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 231,
        'endLine' => 231,
        'startColumn' => 5,
        'endColumn' => 65,
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
          'attributes' => 
          array (
            'name' => 'attributes',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 253,
                'endLine' => 253,
                'startTokenPos' => 562,
                'startFilePos' => 6365,
                'endTokenPos' => 563,
                'endFilePos' => 6366,
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
            'startLine' => 253,
            'endLine' => 253,
            'startColumn' => 33,
            'endColumn' => 54,
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
 * Create a new Eloquent model instance.
 *
 * @param  array  $attributes
 * @return void
 */',
        'startLine' => 253,
        'endLine' => 262,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'bootIfNotBooted' => 
      array (
        'name' => 'bootIfNotBooted',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Check if the model needs to be booted and if so, do it.
 *
 * @return void
 */',
        'startLine' => 269,
        'endLine' => 282,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'booting' => 
      array (
        'name' => 'booting',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Perform any actions required before the model boots.
 *
 * @return void
 */',
        'startLine' => 289,
        'endLine' => 292,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'boot' => 
      array (
        'name' => 'boot',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Bootstrap the model and its traits.
 *
 * @return void
 */',
        'startLine' => 299,
        'endLine' => 302,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'bootTraits' => 
      array (
        'name' => 'bootTraits',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Boot all of the bootable traits on the model.
 *
 * @return void
 */',
        'startLine' => 309,
        'endLine' => 334,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'initializeTraits' => 
      array (
        'name' => 'initializeTraits',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Initialize any initializable traits on the model.
 *
 * @return void
 */',
        'startLine' => 341,
        'endLine' => 346,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'booted' => 
      array (
        'name' => 'booted',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Perform any actions required after the model boots.
 *
 * @return void
 */',
        'startLine' => 353,
        'endLine' => 356,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'clearBootedModels' => 
      array (
        'name' => 'clearBootedModels',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Clear the list of booted models so they will be re-booted.
 *
 * @return void
 */',
        'startLine' => 363,
        'endLine' => 368,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'withoutTouching' => 
      array (
        'name' => 'withoutTouching',
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
            'startLine' => 376,
            'endLine' => 376,
            'startColumn' => 44,
            'endColumn' => 61,
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
 * Disables relationship model touching for the current class during given callback scope.
 *
 * @param  callable  $callback
 * @return void
 */',
        'startLine' => 376,
        'endLine' => 379,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'withoutTouchingOn' => 
      array (
        'name' => 'withoutTouchingOn',
        'parameters' => 
        array (
          'models' => 
          array (
            'name' => 'models',
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
            'startLine' => 388,
            'endLine' => 388,
            'startColumn' => 46,
            'endColumn' => 58,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
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
            'startLine' => 388,
            'endLine' => 388,
            'startColumn' => 61,
            'endColumn' => 78,
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
 * Disables relationship model touching for the given model classes during given callback scope.
 *
 * @param  array  $models
 * @param  callable  $callback
 * @return void
 */',
        'startLine' => 388,
        'endLine' => 397,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'isIgnoringTouch' => 
      array (
        'name' => 'isIgnoringTouch',
        'parameters' => 
        array (
          'class' => 
          array (
            'name' => 'class',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 405,
                'endLine' => 405,
                'startTokenPos' => 1144,
                'startFilePos' => 9936,
                'endTokenPos' => 1144,
                'endFilePos' => 9939,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 405,
            'endLine' => 405,
            'startColumn' => 44,
            'endColumn' => 56,
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
 * Determine if the given model is ignoring touches.
 *
 * @param  string|null  $class
 * @return bool
 */',
        'startLine' => 405,
        'endLine' => 420,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'shouldBeStrict' => 
      array (
        'name' => 'shouldBeStrict',
        'parameters' => 
        array (
          'shouldBeStrict' => 
          array (
            'name' => 'shouldBeStrict',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 428,
                'endLine' => 428,
                'startTokenPos' => 1262,
                'startFilePos' => 10601,
                'endTokenPos' => 1262,
                'endFilePos' => 10604,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'bool',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 428,
            'endLine' => 428,
            'startColumn' => 43,
            'endColumn' => 69,
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
 * Indicate that models should prevent lazy loading, silently discarding attributes, and accessing missing attributes.
 *
 * @param  bool  $shouldBeStrict
 * @return void
 */',
        'startLine' => 428,
        'endLine' => 433,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'preventLazyLoading' => 
      array (
        'name' => 'preventLazyLoading',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 441,
                'endLine' => 441,
                'startTokenPos' => 1307,
                'startFilePos' => 10996,
                'endTokenPos' => 1307,
                'endFilePos' => 10999,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 441,
            'endLine' => 441,
            'startColumn' => 47,
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
 * Prevent model relationships from being lazy loaded.
 *
 * @param  bool  $value
 * @return void
 */',
        'startLine' => 441,
        'endLine' => 444,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'handleLazyLoadingViolationUsing' => 
      array (
        'name' => 'handleLazyLoadingViolationUsing',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
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
            'startLine' => 452,
            'endLine' => 452,
            'startColumn' => 60,
            'endColumn' => 78,
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
 * Register a callback that is responsible for handling lazy loading violations.
 *
 * @param  callable|null  $callback
 * @return void
 */',
        'startLine' => 452,
        'endLine' => 455,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'preventSilentlyDiscardingAttributes' => 
      array (
        'name' => 'preventSilentlyDiscardingAttributes',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 463,
                'endLine' => 463,
                'startTokenPos' => 1366,
                'startFilePos' => 11606,
                'endTokenPos' => 1366,
                'endFilePos' => 11609,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 463,
            'endLine' => 463,
            'startColumn' => 64,
            'endColumn' => 76,
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
 * Prevent non-fillable attributes from being silently discarded.
 *
 * @param  bool  $value
 * @return void
 */',
        'startLine' => 463,
        'endLine' => 466,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'handleDiscardedAttributeViolationUsing' => 
      array (
        'name' => 'handleDiscardedAttributeViolationUsing',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
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
            'startLine' => 474,
            'endLine' => 474,
            'startColumn' => 67,
            'endColumn' => 85,
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
 * Register a callback that is responsible for handling discarded attribute violations.
 *
 * @param  callable|null  $callback
 * @return void
 */',
        'startLine' => 474,
        'endLine' => 477,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'preventAccessingMissingAttributes' => 
      array (
        'name' => 'preventAccessingMissingAttributes',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 485,
                'endLine' => 485,
                'startTokenPos' => 1425,
                'startFilePos' => 12247,
                'endTokenPos' => 1425,
                'endFilePos' => 12250,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 485,
            'endLine' => 485,
            'startColumn' => 62,
            'endColumn' => 74,
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
 * Prevent accessing missing attributes on retrieved models.
 *
 * @param  bool  $value
 * @return void
 */',
        'startLine' => 485,
        'endLine' => 488,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'handleMissingAttributeViolationUsing' => 
      array (
        'name' => 'handleMissingAttributeViolationUsing',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
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
            'startLine' => 496,
            'endLine' => 496,
            'startColumn' => 65,
            'endColumn' => 83,
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
 * Register a callback that is responsible for handling missing attribute violations.
 *
 * @param  callable|null  $callback
 * @return void
 */',
        'startLine' => 496,
        'endLine' => 499,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'withoutBroadcasting' => 
      array (
        'name' => 'withoutBroadcasting',
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
            'startLine' => 507,
            'endLine' => 507,
            'startColumn' => 48,
            'endColumn' => 65,
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
 * Execute a callback without broadcasting any model events for all model types.
 *
 * @param  callable  $callback
 * @return mixed
 */',
        'startLine' => 507,
        'endLine' => 518,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'fill' => 
      array (
        'name' => 'fill',
        'parameters' => 
        array (
          'attributes' => 
          array (
            'name' => 'attributes',
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
            'startLine' => 528,
            'endLine' => 528,
            'startColumn' => 26,
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
 * Fill the model with an array of attributes.
 *
 * @param  array  $attributes
 * @return $this
 *
 * @throws \\Illuminate\\Database\\Eloquent\\MassAssignmentException
 */',
        'startLine' => 528,
        'endLine' => 568,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'forceFill' => 
      array (
        'name' => 'forceFill',
        'parameters' => 
        array (
          'attributes' => 
          array (
            'name' => 'attributes',
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
            'startLine' => 576,
            'endLine' => 576,
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
 * Fill the model with an array of attributes. Force mass assignment.
 *
 * @param  array  $attributes
 * @return $this
 */',
        'startLine' => 576,
        'endLine' => 579,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'qualifyColumn' => 
      array (
        'name' => 'qualifyColumn',
        'parameters' => 
        array (
          'column' => 
          array (
            'name' => 'column',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 587,
            'endLine' => 587,
            'startColumn' => 35,
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
 * Qualify the given column name by the model\'s table.
 *
 * @param  string  $column
 * @return string
 */',
        'startLine' => 587,
        'endLine' => 594,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'qualifyColumns' => 
      array (
        'name' => 'qualifyColumns',
        'parameters' => 
        array (
          'columns' => 
          array (
            'name' => 'columns',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 602,
            'endLine' => 602,
            'startColumn' => 36,
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
 * Qualify the given columns with the model\'s table.
 *
 * @param  array  $columns
 * @return array
 */',
        'startLine' => 602,
        'endLine' => 607,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'newInstance' => 
      array (
        'name' => 'newInstance',
        'parameters' => 
        array (
          'attributes' => 
          array (
            'name' => 'attributes',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 616,
                'endLine' => 616,
                'startTokenPos' => 1972,
                'startFilePos' => 16274,
                'endTokenPos' => 1973,
                'endFilePos' => 16275,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 616,
            'endLine' => 616,
            'startColumn' => 33,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'exists' => 
          array (
            'name' => 'exists',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 616,
                'endLine' => 616,
                'startTokenPos' => 1980,
                'startFilePos' => 16288,
                'endTokenPos' => 1980,
                'endFilePos' => 16292,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 616,
            'endLine' => 616,
            'startColumn' => 51,
            'endColumn' => 65,
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
 * Create a new instance of the given model.
 *
 * @param  array  $attributes
 * @param  bool  $exists
 * @return static
 */',
        'startLine' => 616,
        'endLine' => 636,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'newFromBuilder' => 
      array (
        'name' => 'newFromBuilder',
        'parameters' => 
        array (
          'attributes' => 
          array (
            'name' => 'attributes',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 645,
                'endLine' => 645,
                'startTokenPos' => 2074,
                'startFilePos' => 17076,
                'endTokenPos' => 2075,
                'endFilePos' => 17077,
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
            'startColumn' => 36,
            'endColumn' => 51,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'connection' => 
          array (
            'name' => 'connection',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 645,
                'endLine' => 645,
                'startTokenPos' => 2082,
                'startFilePos' => 17094,
                'endTokenPos' => 2082,
                'endFilePos' => 17097,
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
            'startColumn' => 54,
            'endColumn' => 71,
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
 * Create a new model instance that is existing.
 *
 * @param  array  $attributes
 * @param  string|null  $connection
 * @return static
 */',
        'startLine' => 645,
        'endLine' => 656,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'on' => 
      array (
        'name' => 'on',
        'parameters' => 
        array (
          'connection' => 
          array (
            'name' => 'connection',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 664,
                'endLine' => 664,
                'startTokenPos' => 2165,
                'startFilePos' => 17597,
                'endTokenPos' => 2165,
                'endFilePos' => 17600,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 664,
            'endLine' => 664,
            'startColumn' => 31,
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
 * Begin querying the model on a given connection.
 *
 * @param  string|null  $connection
 * @return \\Illuminate\\Database\\Eloquent\\Builder<static>
 */',
        'startLine' => 664,
        'endLine' => 674,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'onWriteConnection' => 
      array (
        'name' => 'onWriteConnection',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Begin querying the model on the write connection.
 *
 * @return \\Illuminate\\Database\\Eloquent\\Builder<static>
 */',
        'startLine' => 681,
        'endLine' => 684,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'all' => 
      array (
        'name' => 'all',
        'parameters' => 
        array (
          'columns' => 
          array (
            'name' => 'columns',
            'default' => 
            array (
              'code' => '[\'*\']',
              'attributes' => 
              array (
                'startLine' => 692,
                'endLine' => 692,
                'startTokenPos' => 2247,
                'startFilePos' => 18474,
                'endTokenPos' => 2249,
                'endFilePos' => 18478,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 692,
            'endLine' => 692,
            'startColumn' => 32,
            'endColumn' => 47,
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
 * Get all of the models from the database.
 *
 * @param  array|string  $columns
 * @return \\Illuminate\\Database\\Eloquent\\Collection<int, static>
 */',
        'startLine' => 692,
        'endLine' => 697,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'with' => 
      array (
        'name' => 'with',
        'parameters' => 
        array (
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
            'startLine' => 705,
            'endLine' => 705,
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
 * Begin querying a model with eager loading.
 *
 * @param  array|string  $relations
 * @return \\Illuminate\\Database\\Eloquent\\Builder<static>
 */',
        'startLine' => 705,
        'endLine' => 710,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'load' => 
      array (
        'name' => 'load',
        'parameters' => 
        array (
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
            'startLine' => 718,
            'endLine' => 718,
            'startColumn' => 26,
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
 * Eager load relations on the model.
 *
 * @param  array|string  $relations
 * @return $this
 */',
        'startLine' => 718,
        'endLine' => 727,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
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
            'startLine' => 736,
            'endLine' => 736,
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
            'startLine' => 736,
            'endLine' => 736,
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
 * Eager load relationships on the polymorphic relation of a model.
 *
 * @param  string  $relation
 * @param  array  $relations
 * @return $this
 */',
        'startLine' => 736,
        'endLine' => 747,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'loadMissing' => 
      array (
        'name' => 'loadMissing',
        'parameters' => 
        array (
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
            'startLine' => 755,
            'endLine' => 755,
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
 * Eager load relations on the model if they are not already eager loaded.
 *
 * @param  array|string  $relations
 * @return $this
 */',
        'startLine' => 755,
        'endLine' => 762,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'loadAggregate' => 
      array (
        'name' => 'loadAggregate',
        'parameters' => 
        array (
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
            'startLine' => 772,
            'endLine' => 772,
            'startColumn' => 35,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'column' => 
          array (
            'name' => 'column',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 772,
            'endLine' => 772,
            'startColumn' => 47,
            'endColumn' => 53,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'function' => 
          array (
            'name' => 'function',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 772,
                'endLine' => 772,
                'startTokenPos' => 2543,
                'startFilePos' => 20457,
                'endTokenPos' => 2543,
                'endFilePos' => 20460,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 772,
            'endLine' => 772,
            'startColumn' => 56,
            'endColumn' => 71,
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
 * Eager load relation\'s column aggregations on the model.
 *
 * @param  array|string  $relations
 * @param  string  $column
 * @param  string|null  $function
 * @return $this
 */',
        'startLine' => 772,
        'endLine' => 777,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'loadCount' => 
      array (
        'name' => 'loadCount',
        'parameters' => 
        array (
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
            'startLine' => 785,
            'endLine' => 785,
            'startColumn' => 31,
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
 * Eager load relation counts on the model.
 *
 * @param  array|string  $relations
 * @return $this
 */',
        'startLine' => 785,
        'endLine' => 790,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'loadMax' => 
      array (
        'name' => 'loadMax',
        'parameters' => 
        array (
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
            'startLine' => 799,
            'endLine' => 799,
            'startColumn' => 29,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'column' => 
          array (
            'name' => 'column',
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
 * Eager load relation max column values on the model.
 *
 * @param  array|string  $relations
 * @param  string  $column
 * @return $this
 */',
        'startLine' => 799,
        'endLine' => 802,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'loadMin' => 
      array (
        'name' => 'loadMin',
        'parameters' => 
        array (
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
            'startLine' => 811,
            'endLine' => 811,
            'startColumn' => 29,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'column' => 
          array (
            'name' => 'column',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 811,
            'endLine' => 811,
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
 * Eager load relation min column values on the model.
 *
 * @param  array|string  $relations
 * @param  string  $column
 * @return $this
 */',
        'startLine' => 811,
        'endLine' => 814,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'loadSum' => 
      array (
        'name' => 'loadSum',
        'parameters' => 
        array (
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
            'startLine' => 823,
            'endLine' => 823,
            'startColumn' => 29,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'column' => 
          array (
            'name' => 'column',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 823,
            'endLine' => 823,
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
 * Eager load relation\'s column summations on the model.
 *
 * @param  array|string  $relations
 * @param  string  $column
 * @return $this
 */',
        'startLine' => 823,
        'endLine' => 826,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'loadAvg' => 
      array (
        'name' => 'loadAvg',
        'parameters' => 
        array (
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
            'startLine' => 835,
            'endLine' => 835,
            'startColumn' => 29,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'column' => 
          array (
            'name' => 'column',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 835,
            'endLine' => 835,
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
 * Eager load relation average column values on the model.
 *
 * @param  array|string  $relations
 * @param  string  $column
 * @return $this
 */',
        'startLine' => 835,
        'endLine' => 838,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'loadExists' => 
      array (
        'name' => 'loadExists',
        'parameters' => 
        array (
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
            'startLine' => 846,
            'endLine' => 846,
            'startColumn' => 32,
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
 * Eager load related model existence values on the model.
 *
 * @param  array|string  $relations
 * @return $this
 */',
        'startLine' => 846,
        'endLine' => 849,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'loadMorphAggregate' => 
      array (
        'name' => 'loadMorphAggregate',
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
            'startLine' => 860,
            'endLine' => 860,
            'startColumn' => 40,
            'endColumn' => 48,
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
            'startLine' => 860,
            'endLine' => 860,
            'startColumn' => 51,
            'endColumn' => 60,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'column' => 
          array (
            'name' => 'column',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 860,
            'endLine' => 860,
            'startColumn' => 63,
            'endColumn' => 69,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'function' => 
          array (
            'name' => 'function',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 860,
                'endLine' => 860,
                'startTokenPos' => 2815,
                'startFilePos' => 22740,
                'endTokenPos' => 2815,
                'endFilePos' => 22743,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 860,
            'endLine' => 860,
            'startColumn' => 72,
            'endColumn' => 87,
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
 * Eager load relationship column aggregation on the polymorphic relation of a model.
 *
 * @param  string  $relation
 * @param  array  $relations
 * @param  string  $column
 * @param  string|null  $function
 * @return $this
 */',
        'startLine' => 860,
        'endLine' => 871,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
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
            'startLine' => 880,
            'endLine' => 880,
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
            'startLine' => 880,
            'endLine' => 880,
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
 * Eager load relationship counts on the polymorphic relation of a model.
 *
 * @param  string  $relation
 * @param  array  $relations
 * @return $this
 */',
        'startLine' => 880,
        'endLine' => 883,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'loadMorphMax' => 
      array (
        'name' => 'loadMorphMax',
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
            'startLine' => 893,
            'endLine' => 893,
            'startColumn' => 34,
            'endColumn' => 42,
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
            'startLine' => 893,
            'endLine' => 893,
            'startColumn' => 45,
            'endColumn' => 54,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'column' => 
          array (
            'name' => 'column',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 893,
            'endLine' => 893,
            'startColumn' => 57,
            'endColumn' => 63,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Eager load relationship max column values on the polymorphic relation of a model.
 *
 * @param  string  $relation
 * @param  array  $relations
 * @param  string  $column
 * @return $this
 */',
        'startLine' => 893,
        'endLine' => 896,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'loadMorphMin' => 
      array (
        'name' => 'loadMorphMin',
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
            'startLine' => 906,
            'endLine' => 906,
            'startColumn' => 34,
            'endColumn' => 42,
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
            'startLine' => 906,
            'endLine' => 906,
            'startColumn' => 45,
            'endColumn' => 54,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'column' => 
          array (
            'name' => 'column',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 906,
            'endLine' => 906,
            'startColumn' => 57,
            'endColumn' => 63,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Eager load relationship min column values on the polymorphic relation of a model.
 *
 * @param  string  $relation
 * @param  array  $relations
 * @param  string  $column
 * @return $this
 */',
        'startLine' => 906,
        'endLine' => 909,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'loadMorphSum' => 
      array (
        'name' => 'loadMorphSum',
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
            'startLine' => 919,
            'endLine' => 919,
            'startColumn' => 34,
            'endColumn' => 42,
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
            'startLine' => 919,
            'endLine' => 919,
            'startColumn' => 45,
            'endColumn' => 54,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'column' => 
          array (
            'name' => 'column',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 919,
            'endLine' => 919,
            'startColumn' => 57,
            'endColumn' => 63,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Eager load relationship column summations on the polymorphic relation of a model.
 *
 * @param  string  $relation
 * @param  array  $relations
 * @param  string  $column
 * @return $this
 */',
        'startLine' => 919,
        'endLine' => 922,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'loadMorphAvg' => 
      array (
        'name' => 'loadMorphAvg',
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
            'startLine' => 932,
            'endLine' => 932,
            'startColumn' => 34,
            'endColumn' => 42,
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
            'startLine' => 932,
            'endLine' => 932,
            'startColumn' => 45,
            'endColumn' => 54,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'column' => 
          array (
            'name' => 'column',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 932,
            'endLine' => 932,
            'startColumn' => 57,
            'endColumn' => 63,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Eager load relationship average column values on the polymorphic relation of a model.
 *
 * @param  string  $relation
 * @param  array  $relations
 * @param  string  $column
 * @return $this
 */',
        'startLine' => 932,
        'endLine' => 935,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'increment' => 
      array (
        'name' => 'increment',
        'parameters' => 
        array (
          'column' => 
          array (
            'name' => 'column',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 945,
            'endLine' => 945,
            'startColumn' => 34,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'amount' => 
          array (
            'name' => 'amount',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 945,
                'endLine' => 945,
                'startTokenPos' => 3100,
                'startFilePos' => 25140,
                'endTokenPos' => 3100,
                'endFilePos' => 25140,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 945,
            'endLine' => 945,
            'startColumn' => 43,
            'endColumn' => 53,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'extra' => 
          array (
            'name' => 'extra',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 945,
                'endLine' => 945,
                'startTokenPos' => 3109,
                'startFilePos' => 25158,
                'endTokenPos' => 3110,
                'endFilePos' => 25159,
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
            'startLine' => 945,
            'endLine' => 945,
            'startColumn' => 56,
            'endColumn' => 72,
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
 * Increment a column\'s value by a given amount.
 *
 * @param  string  $column
 * @param  float|int  $amount
 * @param  array  $extra
 * @return int
 */',
        'startLine' => 945,
        'endLine' => 948,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'decrement' => 
      array (
        'name' => 'decrement',
        'parameters' => 
        array (
          'column' => 
          array (
            'name' => 'column',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 958,
            'endLine' => 958,
            'startColumn' => 34,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'amount' => 
          array (
            'name' => 'amount',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 958,
                'endLine' => 958,
                'startTokenPos' => 3151,
                'startFilePos' => 25499,
                'endTokenPos' => 3151,
                'endFilePos' => 25499,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 958,
            'endLine' => 958,
            'startColumn' => 43,
            'endColumn' => 53,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'extra' => 
          array (
            'name' => 'extra',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 958,
                'endLine' => 958,
                'startTokenPos' => 3160,
                'startFilePos' => 25517,
                'endTokenPos' => 3161,
                'endFilePos' => 25518,
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
            'startLine' => 958,
            'endLine' => 958,
            'startColumn' => 56,
            'endColumn' => 72,
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
 * Decrement a column\'s value by a given amount.
 *
 * @param  string  $column
 * @param  float|int  $amount
 * @param  array  $extra
 * @return int
 */',
        'startLine' => 958,
        'endLine' => 961,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'incrementOrDecrement' => 
      array (
        'name' => 'incrementOrDecrement',
        'parameters' => 
        array (
          'column' => 
          array (
            'name' => 'column',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 972,
            'endLine' => 972,
            'startColumn' => 45,
            'endColumn' => 51,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'amount' => 
          array (
            'name' => 'amount',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 972,
            'endLine' => 972,
            'startColumn' => 54,
            'endColumn' => 60,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'extra' => 
          array (
            'name' => 'extra',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 972,
            'endLine' => 972,
            'startColumn' => 63,
            'endColumn' => 68,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
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
            'startLine' => 972,
            'endLine' => 972,
            'startColumn' => 71,
            'endColumn' => 77,
            'parameterIndex' => 3,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Run the increment or decrement method on the model.
 *
 * @param  string  $column
 * @param  float|int  $amount
 * @param  array  $extra
 * @param  string  $method
 * @return int
 */',
        'startLine' => 972,
        'endLine' => 999,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'update' => 
      array (
        'name' => 'update',
        'parameters' => 
        array (
          'attributes' => 
          array (
            'name' => 'attributes',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 1008,
                'endLine' => 1008,
                'startTokenPos' => 3466,
                'startFilePos' => 27077,
                'endTokenPos' => 3467,
                'endFilePos' => 27078,
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
            'startLine' => 1008,
            'endLine' => 1008,
            'startColumn' => 28,
            'endColumn' => 49,
            'parameterIndex' => 0,
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
                'startLine' => 1008,
                'endLine' => 1008,
                'startTokenPos' => 3476,
                'startFilePos' => 27098,
                'endTokenPos' => 3477,
                'endFilePos' => 27099,
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
            'startLine' => 1008,
            'endLine' => 1008,
            'startColumn' => 52,
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
 * Update the model in the database.
 *
 * @param  array  $attributes
 * @param  array  $options
 * @return bool
 */',
        'startLine' => 1008,
        'endLine' => 1015,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'updateOrFail' => 
      array (
        'name' => 'updateOrFail',
        'parameters' => 
        array (
          'attributes' => 
          array (
            'name' => 'attributes',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 1026,
                'endLine' => 1026,
                'startTokenPos' => 3532,
                'startFilePos' => 27496,
                'endTokenPos' => 3533,
                'endFilePos' => 27497,
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
            'startLine' => 1026,
            'endLine' => 1026,
            'startColumn' => 34,
            'endColumn' => 55,
            'parameterIndex' => 0,
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
                'startLine' => 1026,
                'endLine' => 1026,
                'startTokenPos' => 3542,
                'startFilePos' => 27517,
                'endTokenPos' => 3543,
                'endFilePos' => 27518,
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
            'startLine' => 1026,
            'endLine' => 1026,
            'startColumn' => 58,
            'endColumn' => 76,
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
 * Update the model in the database within a transaction.
 *
 * @param  array  $attributes
 * @param  array  $options
 * @return bool
 *
 * @throws \\Throwable
 */',
        'startLine' => 1026,
        'endLine' => 1033,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'updateQuietly' => 
      array (
        'name' => 'updateQuietly',
        'parameters' => 
        array (
          'attributes' => 
          array (
            'name' => 'attributes',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 1042,
                'endLine' => 1042,
                'startTokenPos' => 3598,
                'startFilePos' => 27895,
                'endTokenPos' => 3599,
                'endFilePos' => 27896,
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
            'startLine' => 1042,
            'endLine' => 1042,
            'startColumn' => 35,
            'endColumn' => 56,
            'parameterIndex' => 0,
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
                'startLine' => 1042,
                'endLine' => 1042,
                'startTokenPos' => 3608,
                'startFilePos' => 27916,
                'endTokenPos' => 3609,
                'endFilePos' => 27917,
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
            'startLine' => 1042,
            'endLine' => 1042,
            'startColumn' => 59,
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
 * Update the model in the database without raising any events.
 *
 * @param  array  $attributes
 * @param  array  $options
 * @return bool
 */',
        'startLine' => 1042,
        'endLine' => 1049,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'incrementQuietly' => 
      array (
        'name' => 'incrementQuietly',
        'parameters' => 
        array (
          'column' => 
          array (
            'name' => 'column',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1059,
            'endLine' => 1059,
            'startColumn' => 41,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'amount' => 
          array (
            'name' => 'amount',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 1059,
                'endLine' => 1059,
                'startTokenPos' => 3665,
                'startFilePos' => 28340,
                'endTokenPos' => 3665,
                'endFilePos' => 28340,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1059,
            'endLine' => 1059,
            'startColumn' => 50,
            'endColumn' => 60,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'extra' => 
          array (
            'name' => 'extra',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 1059,
                'endLine' => 1059,
                'startTokenPos' => 3674,
                'startFilePos' => 28358,
                'endTokenPos' => 3675,
                'endFilePos' => 28359,
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
            'startLine' => 1059,
            'endLine' => 1059,
            'startColumn' => 63,
            'endColumn' => 79,
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
 * Increment a column\'s value by a given amount without raising any events.
 *
 * @param  string  $column
 * @param  float|int  $amount
 * @param  array  $extra
 * @return int
 */',
        'startLine' => 1059,
        'endLine' => 1064,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'decrementQuietly' => 
      array (
        'name' => 'decrementQuietly',
        'parameters' => 
        array (
          'column' => 
          array (
            'name' => 'column',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1074,
            'endLine' => 1074,
            'startColumn' => 41,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'amount' => 
          array (
            'name' => 'amount',
            'default' => 
            array (
              'code' => '1',
              'attributes' => 
              array (
                'startLine' => 1074,
                'endLine' => 1074,
                'startTokenPos' => 3730,
                'startFilePos' => 28787,
                'endTokenPos' => 3730,
                'endFilePos' => 28787,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1074,
            'endLine' => 1074,
            'startColumn' => 50,
            'endColumn' => 60,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'extra' => 
          array (
            'name' => 'extra',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 1074,
                'endLine' => 1074,
                'startTokenPos' => 3739,
                'startFilePos' => 28805,
                'endTokenPos' => 3740,
                'endFilePos' => 28806,
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
            'startLine' => 1074,
            'endLine' => 1074,
            'startColumn' => 63,
            'endColumn' => 79,
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
 * Decrement a column\'s value by a given amount without raising any events.
 *
 * @param  string  $column
 * @param  float|int  $amount
 * @param  array  $extra
 * @return int
 */',
        'startLine' => 1074,
        'endLine' => 1079,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'push' => 
      array (
        'name' => 'push',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Save the model and all of its relationships.
 *
 * @return bool
 */',
        'startLine' => 1086,
        'endLine' => 1109,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'pushQuietly' => 
      array (
        'name' => 'pushQuietly',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Save the model and all of its relationships without raising any events to the parent model.
 *
 * @return bool
 */',
        'startLine' => 1116,
        'endLine' => 1119,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'saveQuietly' => 
      array (
        'name' => 'saveQuietly',
        'parameters' => 
        array (
          'options' => 
          array (
            'name' => 'options',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 1127,
                'endLine' => 1127,
                'startTokenPos' => 3974,
                'startFilePos' => 30337,
                'endTokenPos' => 3975,
                'endFilePos' => 30338,
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
            'startLine' => 1127,
            'endLine' => 1127,
            'startColumn' => 33,
            'endColumn' => 51,
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
 * Save the model to the database without raising any events.
 *
 * @param  array  $options
 * @return bool
 */',
        'startLine' => 1127,
        'endLine' => 1130,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'save' => 
      array (
        'name' => 'save',
        'parameters' => 
        array (
          'options' => 
          array (
            'name' => 'options',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 1138,
                'endLine' => 1138,
                'startTokenPos' => 4018,
                'startFilePos' => 30579,
                'endTokenPos' => 4019,
                'endFilePos' => 30580,
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
            'startLine' => 1138,
            'endLine' => 1138,
            'startColumn' => 26,
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
 * Save the model to the database.
 *
 * @param  array  $options
 * @return bool
 */',
        'startLine' => 1138,
        'endLine' => 1179,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'saveOrFail' => 
      array (
        'name' => 'saveOrFail',
        'parameters' => 
        array (
          'options' => 
          array (
            'name' => 'options',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 1189,
                'endLine' => 1189,
                'startTokenPos' => 4223,
                'startFilePos' => 32468,
                'endTokenPos' => 4224,
                'endFilePos' => 32469,
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
            'startLine' => 1189,
            'endLine' => 1189,
            'startColumn' => 32,
            'endColumn' => 50,
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
 * Save the model to the database within a transaction.
 *
 * @param  array  $options
 * @return bool
 *
 * @throws \\Throwable
 */',
        'startLine' => 1189,
        'endLine' => 1192,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'finishSave' => 
      array (
        'name' => 'finishSave',
        'parameters' => 
        array (
          'options' => 
          array (
            'name' => 'options',
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
            'startLine' => 1200,
            'endLine' => 1200,
            'startColumn' => 35,
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
 * Perform any actions that are necessary after the model is saved.
 *
 * @param  array  $options
 * @return void
 */',
        'startLine' => 1200,
        'endLine' => 1209,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'performUpdate' => 
      array (
        'name' => 'performUpdate',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Builder',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1217,
            'endLine' => 1217,
            'startColumn' => 38,
            'endColumn' => 51,
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
 * Perform a model update operation.
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Builder<static>  $query
 * @return bool
 */',
        'startLine' => 1217,
        'endLine' => 1247,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'setKeysForSelectQuery' => 
      array (
        'name' => 'setKeysForSelectQuery',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1255,
            'endLine' => 1255,
            'startColumn' => 46,
            'endColumn' => 51,
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
 * Set the keys for a select query.
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Builder<static>  $query
 * @return \\Illuminate\\Database\\Eloquent\\Builder<static>
 */',
        'startLine' => 1255,
        'endLine' => 1260,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'getKeyForSelectQuery' => 
      array (
        'name' => 'getKeyForSelectQuery',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the primary key value for a select query.
 *
 * @return mixed
 */',
        'startLine' => 1267,
        'endLine' => 1270,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'setKeysForSaveQuery' => 
      array (
        'name' => 'setKeysForSaveQuery',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1278,
            'endLine' => 1278,
            'startColumn' => 44,
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
 * Set the keys for a save update query.
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Builder<static>  $query
 * @return \\Illuminate\\Database\\Eloquent\\Builder<static>
 */',
        'startLine' => 1278,
        'endLine' => 1283,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'getKeyForSaveQuery' => 
      array (
        'name' => 'getKeyForSaveQuery',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the primary key value for a save query.
 *
 * @return mixed
 */',
        'startLine' => 1290,
        'endLine' => 1293,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'performInsert' => 
      array (
        'name' => 'performInsert',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Builder',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1301,
            'endLine' => 1301,
            'startColumn' => 38,
            'endColumn' => 51,
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
 * Perform a model insert operation.
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Builder<static>  $query
 * @return bool
 */',
        'startLine' => 1301,
        'endLine' => 1348,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'insertAndSetId' => 
      array (
        'name' => 'insertAndSetId',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Builder',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1357,
            'endLine' => 1357,
            'startColumn' => 39,
            'endColumn' => 52,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'attributes' => 
          array (
            'name' => 'attributes',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1357,
            'endLine' => 1357,
            'startColumn' => 55,
            'endColumn' => 65,
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
 * Insert the given attributes and set the ID on the model.
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Builder<static>  $query
 * @param  array  $attributes
 * @return void
 */',
        'startLine' => 1357,
        'endLine' => 1362,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'destroy' => 
      array (
        'name' => 'destroy',
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
            'startLine' => 1370,
            'endLine' => 1370,
            'startColumn' => 36,
            'endColumn' => 39,
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
        'startLine' => 1370,
        'endLine' => 1400,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'delete' => 
      array (
        'name' => 'delete',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Delete the model from the database.
 *
 * @return bool|null
 *
 * @throws \\LogicException
 */',
        'startLine' => 1409,
        'endLine' => 1441,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'deleteQuietly' => 
      array (
        'name' => 'deleteQuietly',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Delete the model from the database without raising any events.
 *
 * @return bool
 */',
        'startLine' => 1448,
        'endLine' => 1451,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'deleteOrFail' => 
      array (
        'name' => 'deleteOrFail',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Delete the model from the database within a transaction.
 *
 * @return bool|null
 *
 * @throws \\Throwable
 */',
        'startLine' => 1460,
        'endLine' => 1467,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
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
 * This method protects developers from running forceDelete when the trait is missing.
 *
 * @return bool|null
 */',
        'startLine' => 1476,
        'endLine' => 1479,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
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
            'startLine' => 1489,
            'endLine' => 1489,
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
 * Force a hard destroy on a soft deleted model.
 *
 * This method protects developers from running forceDestroy when the trait is missing.
 *
 * @param  \\Illuminate\\Support\\Collection|array|int|string  $ids
 * @return bool|null
 */',
        'startLine' => 1489,
        'endLine' => 1492,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
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
 * @return void
 */',
        'startLine' => 1499,
        'endLine' => 1504,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'query' => 
      array (
        'name' => 'query',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Begin querying the model.
 *
 * @return \\Illuminate\\Database\\Eloquent\\Builder<static>
 */',
        'startLine' => 1511,
        'endLine' => 1514,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'newQuery' => 
      array (
        'name' => 'newQuery',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get a new query builder for the model\'s table.
 *
 * @return \\Illuminate\\Database\\Eloquent\\Builder<static>
 */',
        'startLine' => 1521,
        'endLine' => 1524,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'newModelQuery' => 
      array (
        'name' => 'newModelQuery',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get a new query builder that doesn\'t have any global scopes or eager loading.
 *
 * @return \\Illuminate\\Database\\Eloquent\\Builder<static>
 */',
        'startLine' => 1531,
        'endLine' => 1536,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'newQueryWithoutRelationships' => 
      array (
        'name' => 'newQueryWithoutRelationships',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get a new query builder with no relationships loaded.
 *
 * @return \\Illuminate\\Database\\Eloquent\\Builder<static>
 */',
        'startLine' => 1543,
        'endLine' => 1546,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'registerGlobalScopes' => 
      array (
        'name' => 'registerGlobalScopes',
        'parameters' => 
        array (
          'builder' => 
          array (
            'name' => 'builder',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1554,
            'endLine' => 1554,
            'startColumn' => 42,
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
 * Register the global scopes for this builder instance.
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Builder<static>  $builder
 * @return \\Illuminate\\Database\\Eloquent\\Builder<static>
 */',
        'startLine' => 1554,
        'endLine' => 1561,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'newQueryWithoutScopes' => 
      array (
        'name' => 'newQueryWithoutScopes',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get a new query builder that doesn\'t have any global scopes.
 *
 * @return \\Illuminate\\Database\\Eloquent\\Builder<static>
 */',
        'startLine' => 1568,
        'endLine' => 1573,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'newQueryWithoutScope' => 
      array (
        'name' => 'newQueryWithoutScope',
        'parameters' => 
        array (
          'scope' => 
          array (
            'name' => 'scope',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1581,
            'endLine' => 1581,
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
 * Get a new query instance without a given scope.
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Scope|string  $scope
 * @return \\Illuminate\\Database\\Eloquent\\Builder<static>
 */',
        'startLine' => 1581,
        'endLine' => 1584,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'newQueryForRestoration' => 
      array (
        'name' => 'newQueryForRestoration',
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
            'startLine' => 1592,
            'endLine' => 1592,
            'startColumn' => 44,
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
 * Get a new query to restore one or more models by their queueable IDs.
 *
 * @param  array|int  $ids
 * @return \\Illuminate\\Database\\Eloquent\\Builder<static>
 */',
        'startLine' => 1592,
        'endLine' => 1595,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'newEloquentBuilder' => 
      array (
        'name' => 'newEloquentBuilder',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1603,
            'endLine' => 1603,
            'startColumn' => 40,
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
 * Create a new Eloquent query builder for the model.
 *
 * @param  \\Illuminate\\Database\\Query\\Builder  $query
 * @return \\Illuminate\\Database\\Eloquent\\Builder<*>
 */',
        'startLine' => 1603,
        'endLine' => 1606,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'newBaseQueryBuilder' => 
      array (
        'name' => 'newBaseQueryBuilder',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get a new query builder instance for the connection.
 *
 * @return \\Illuminate\\Database\\Query\\Builder
 */',
        'startLine' => 1613,
        'endLine' => 1616,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'newPivot' => 
      array (
        'name' => 'newPivot',
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
                'name' => 'self',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1628,
            'endLine' => 1628,
            'startColumn' => 30,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'attributes' => 
          array (
            'name' => 'attributes',
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
            'startLine' => 1628,
            'endLine' => 1628,
            'startColumn' => 44,
            'endColumn' => 60,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'table' => 
          array (
            'name' => 'table',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1628,
            'endLine' => 1628,
            'startColumn' => 63,
            'endColumn' => 68,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'exists' => 
          array (
            'name' => 'exists',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1628,
            'endLine' => 1628,
            'startColumn' => 71,
            'endColumn' => 77,
            'parameterIndex' => 3,
            'isOptional' => false,
          ),
          'using' => 
          array (
            'name' => 'using',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1628,
                'endLine' => 1628,
                'startTokenPos' => 5750,
                'startFilePos' => 45286,
                'endTokenPos' => 5750,
                'endFilePos' => 45289,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1628,
            'endLine' => 1628,
            'startColumn' => 80,
            'endColumn' => 92,
            'parameterIndex' => 4,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create a new pivot model instance.
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Model  $parent
 * @param  array  $attributes
 * @param  string  $table
 * @param  bool  $exists
 * @param  string|null  $using
 * @return \\Illuminate\\Database\\Eloquent\\Relations\\Pivot
 */',
        'startLine' => 1628,
        'endLine' => 1632,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'hasNamedScope' => 
      array (
        'name' => 'hasNamedScope',
        'parameters' => 
        array (
          'scope' => 
          array (
            'name' => 'scope',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1640,
            'endLine' => 1640,
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
 * Determine if the model has a given scope.
 *
 * @param  string  $scope
 * @return bool
 */',
        'startLine' => 1640,
        'endLine' => 1643,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'callNamedScope' => 
      array (
        'name' => 'callNamedScope',
        'parameters' => 
        array (
          'scope' => 
          array (
            'name' => 'scope',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1652,
            'endLine' => 1652,
            'startColumn' => 36,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'parameters' => 
          array (
            'name' => 'parameters',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 1652,
                'endLine' => 1652,
                'startTokenPos' => 5846,
                'startFilePos' => 45928,
                'endTokenPos' => 5847,
                'endFilePos' => 45929,
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
            'startLine' => 1652,
            'endLine' => 1652,
            'startColumn' => 44,
            'endColumn' => 65,
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
 * Apply the given named scope if possible.
 *
 * @param  string  $scope
 * @param  array  $parameters
 * @return mixed
 */',
        'startLine' => 1652,
        'endLine' => 1655,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
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
 * Convert the model instance to an array.
 *
 * @return array
 */',
        'startLine' => 1662,
        'endLine' => 1668,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
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
                'startLine' => 1678,
                'endLine' => 1678,
                'startTokenPos' => 5946,
                'startFilePos' => 46562,
                'endTokenPos' => 5946,
                'endFilePos' => 46562,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1678,
            'endLine' => 1678,
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
 * Convert the model instance to JSON.
 *
 * @param  int  $options
 * @return string
 *
 * @throws \\Illuminate\\Database\\Eloquent\\JsonEncodingException
 */',
        'startLine' => 1678,
        'endLine' => 1687,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
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
            'name' => 'mixed',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Convert the object into something JSON serializable.
 *
 * @return mixed
 */',
        'startLine' => 1694,
        'endLine' => 1697,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'fresh' => 
      array (
        'name' => 'fresh',
        'parameters' => 
        array (
          'with' => 
          array (
            'name' => 'with',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 1705,
                'endLine' => 1705,
                'startTokenPos' => 6052,
                'startFilePos' => 47195,
                'endTokenPos' => 6053,
                'endFilePos' => 47196,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1705,
            'endLine' => 1705,
            'startColumn' => 27,
            'endColumn' => 36,
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
 * Reload a fresh model instance from the database.
 *
 * @param  array|string  $with
 * @return static|null
 */',
        'startLine' => 1705,
        'endLine' => 1715,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'refresh' => 
      array (
        'name' => 'refresh',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Reload the current model instance with fresh attributes from the database.
 *
 * @return $this
 */',
        'startLine' => 1722,
        'endLine' => 1743,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'replicate' => 
      array (
        'name' => 'replicate',
        'parameters' => 
        array (
          'except' => 
          array (
            'name' => 'except',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1751,
                'endLine' => 1751,
                'startTokenPos' => 6283,
                'startFilePos' => 48413,
                'endTokenPos' => 6283,
                'endFilePos' => 48416,
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
            'startLine' => 1751,
            'endLine' => 1751,
            'startColumn' => 31,
            'endColumn' => 51,
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
 * Clone the model into a new, non-existing instance.
 *
 * @param  array|null  $except
 * @return static
 */',
        'startLine' => 1751,
        'endLine' => 1772,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'replicateQuietly' => 
      array (
        'name' => 'replicateQuietly',
        'parameters' => 
        array (
          'except' => 
          array (
            'name' => 'except',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1780,
                'endLine' => 1780,
                'startTokenPos' => 6446,
                'startFilePos' => 49305,
                'endTokenPos' => 6446,
                'endFilePos' => 49308,
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
            'startLine' => 1780,
            'endLine' => 1780,
            'startColumn' => 38,
            'endColumn' => 58,
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
 * Clone the model into a new, non-existing instance without raising any events.
 *
 * @param  array|null  $except
 * @return static
 */',
        'startLine' => 1780,
        'endLine' => 1783,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'is' => 
      array (
        'name' => 'is',
        'parameters' => 
        array (
          'model' => 
          array (
            'name' => 'model',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1791,
            'endLine' => 1791,
            'startColumn' => 24,
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
 * Determine if two models have the same ID and belong to the same table.
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Model|null  $model
 * @return bool
 */',
        'startLine' => 1791,
        'endLine' => 1797,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'isNot' => 
      array (
        'name' => 'isNot',
        'parameters' => 
        array (
          'model' => 
          array (
            'name' => 'model',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1805,
            'endLine' => 1805,
            'startColumn' => 27,
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
 * Determine if two models are not the same.
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Model|null  $model
 * @return bool
 */',
        'startLine' => 1805,
        'endLine' => 1808,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'getConnection' => 
      array (
        'name' => 'getConnection',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the database connection for the model.
 *
 * @return \\Illuminate\\Database\\Connection
 */',
        'startLine' => 1815,
        'endLine' => 1818,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'getConnectionName' => 
      array (
        'name' => 'getConnectionName',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the current connection name for the model.
 *
 * @return string|null
 */',
        'startLine' => 1825,
        'endLine' => 1828,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'setConnection' => 
      array (
        'name' => 'setConnection',
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
            'startLine' => 1836,
            'endLine' => 1836,
            'startColumn' => 35,
            'endColumn' => 39,
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
 * Set the connection associated with the model.
 *
 * @param  string|null  $name
 * @return $this
 */',
        'startLine' => 1836,
        'endLine' => 1841,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'resolveConnection' => 
      array (
        'name' => 'resolveConnection',
        'parameters' => 
        array (
          'connection' => 
          array (
            'name' => 'connection',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1849,
                'endLine' => 1849,
                'startTokenPos' => 6667,
                'startFilePos' => 50962,
                'endTokenPos' => 6667,
                'endFilePos' => 50965,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1849,
            'endLine' => 1849,
            'startColumn' => 46,
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
 * Resolve a connection instance.
 *
 * @param  string|null  $connection
 * @return \\Illuminate\\Database\\Connection
 */',
        'startLine' => 1849,
        'endLine' => 1852,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'getConnectionResolver' => 
      array (
        'name' => 'getConnectionResolver',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the connection resolver instance.
 *
 * @return \\Illuminate\\Database\\ConnectionResolverInterface|null
 */',
        'startLine' => 1859,
        'endLine' => 1862,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'setConnectionResolver' => 
      array (
        'name' => 'setConnectionResolver',
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
                'name' => 'Illuminate\\Database\\ConnectionResolverInterface',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1870,
            'endLine' => 1870,
            'startColumn' => 50,
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
 * Set the connection resolver instance.
 *
 * @param  \\Illuminate\\Database\\ConnectionResolverInterface  $resolver
 * @return void
 */',
        'startLine' => 1870,
        'endLine' => 1873,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'unsetConnectionResolver' => 
      array (
        'name' => 'unsetConnectionResolver',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Unset the connection resolver for models.
 *
 * @return void
 */',
        'startLine' => 1880,
        'endLine' => 1883,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'getTable' => 
      array (
        'name' => 'getTable',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the table associated with the model.
 *
 * @return string
 */',
        'startLine' => 1890,
        'endLine' => 1893,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'setTable' => 
      array (
        'name' => 'setTable',
        'parameters' => 
        array (
          'table' => 
          array (
            'name' => 'table',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1901,
            'endLine' => 1901,
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
 * Set the table associated with the model.
 *
 * @param  string  $table
 * @return $this
 */',
        'startLine' => 1901,
        'endLine' => 1906,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'getKeyName' => 
      array (
        'name' => 'getKeyName',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the primary key for the model.
 *
 * @return string
 */',
        'startLine' => 1913,
        'endLine' => 1916,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'setKeyName' => 
      array (
        'name' => 'setKeyName',
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
            'startLine' => 1924,
            'endLine' => 1924,
            'startColumn' => 32,
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
 * Set the primary key for the model.
 *
 * @param  string  $key
 * @return $this
 */',
        'startLine' => 1924,
        'endLine' => 1929,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'getQualifiedKeyName' => 
      array (
        'name' => 'getQualifiedKeyName',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the table qualified key name.
 *
 * @return string
 */',
        'startLine' => 1936,
        'endLine' => 1939,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'getKeyType' => 
      array (
        'name' => 'getKeyType',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the auto-incrementing key type.
 *
 * @return string
 */',
        'startLine' => 1946,
        'endLine' => 1949,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'setKeyType' => 
      array (
        'name' => 'setKeyType',
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
            'startLine' => 1957,
            'endLine' => 1957,
            'startColumn' => 32,
            'endColumn' => 36,
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
 * Set the data type for the primary key.
 *
 * @param  string  $type
 * @return $this
 */',
        'startLine' => 1957,
        'endLine' => 1962,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'getIncrementing' => 
      array (
        'name' => 'getIncrementing',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the value indicating whether the IDs are incrementing.
 *
 * @return bool
 */',
        'startLine' => 1969,
        'endLine' => 1972,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'setIncrementing' => 
      array (
        'name' => 'setIncrementing',
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
            'startLine' => 1980,
            'endLine' => 1980,
            'startColumn' => 37,
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
 * Set whether IDs are incrementing.
 *
 * @param  bool  $value
 * @return $this
 */',
        'startLine' => 1980,
        'endLine' => 1985,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'getKey' => 
      array (
        'name' => 'getKey',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the value of the model\'s primary key.
 *
 * @return mixed
 */',
        'startLine' => 1992,
        'endLine' => 1995,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'getQueueableId' => 
      array (
        'name' => 'getQueueableId',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the queueable identity for the entity.
 *
 * @return mixed
 */',
        'startLine' => 2002,
        'endLine' => 2005,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'getQueueableRelations' => 
      array (
        'name' => 'getQueueableRelations',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the queueable relationships for the entity.
 *
 * @return array
 */',
        'startLine' => 2012,
        'endLine' => 2039,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'getQueueableConnection' => 
      array (
        'name' => 'getQueueableConnection',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the queueable connection for the entity.
 *
 * @return string|null
 */',
        'startLine' => 2046,
        'endLine' => 2049,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'getRouteKey' => 
      array (
        'name' => 'getRouteKey',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the value of the model\'s route key.
 *
 * @return mixed
 */',
        'startLine' => 2056,
        'endLine' => 2059,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'getRouteKeyName' => 
      array (
        'name' => 'getRouteKeyName',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the route key for the model.
 *
 * @return string
 */',
        'startLine' => 2066,
        'endLine' => 2069,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'resolveRouteBinding' => 
      array (
        'name' => 'resolveRouteBinding',
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
            'startLine' => 2078,
            'endLine' => 2078,
            'startColumn' => 41,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'field' => 
          array (
            'name' => 'field',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 2078,
                'endLine' => 2078,
                'startTokenPos' => 7340,
                'startFilePos' => 55815,
                'endTokenPos' => 7340,
                'endFilePos' => 55818,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2078,
            'endLine' => 2078,
            'startColumn' => 49,
            'endColumn' => 61,
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
 * Retrieve the model for a bound value.
 *
 * @param  mixed  $value
 * @param  string|null  $field
 * @return \\Illuminate\\Database\\Eloquent\\Model|null
 */',
        'startLine' => 2078,
        'endLine' => 2081,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'resolveSoftDeletableRouteBinding' => 
      array (
        'name' => 'resolveSoftDeletableRouteBinding',
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
            'startLine' => 2090,
            'endLine' => 2090,
            'startColumn' => 54,
            'endColumn' => 59,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'field' => 
          array (
            'name' => 'field',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 2090,
                'endLine' => 2090,
                'startTokenPos' => 7382,
                'startFilePos' => 56172,
                'endTokenPos' => 7382,
                'endFilePos' => 56175,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2090,
            'endLine' => 2090,
            'startColumn' => 62,
            'endColumn' => 74,
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
 * Retrieve the model for a bound value.
 *
 * @param  mixed  $value
 * @param  string|null  $field
 * @return \\Illuminate\\Database\\Eloquent\\Model|null
 */',
        'startLine' => 2090,
        'endLine' => 2093,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'resolveChildRouteBinding' => 
      array (
        'name' => 'resolveChildRouteBinding',
        'parameters' => 
        array (
          'childType' => 
          array (
            'name' => 'childType',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2103,
            'endLine' => 2103,
            'startColumn' => 46,
            'endColumn' => 55,
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
            'startLine' => 2103,
            'endLine' => 2103,
            'startColumn' => 58,
            'endColumn' => 63,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'field' => 
          array (
            'name' => 'field',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2103,
            'endLine' => 2103,
            'startColumn' => 66,
            'endColumn' => 71,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Retrieve the child model for a bound value.
 *
 * @param  string  $childType
 * @param  mixed  $value
 * @param  string|null  $field
 * @return \\Illuminate\\Database\\Eloquent\\Model|null
 */',
        'startLine' => 2103,
        'endLine' => 2106,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'resolveSoftDeletableChildRouteBinding' => 
      array (
        'name' => 'resolveSoftDeletableChildRouteBinding',
        'parameters' => 
        array (
          'childType' => 
          array (
            'name' => 'childType',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2116,
            'endLine' => 2116,
            'startColumn' => 59,
            'endColumn' => 68,
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
            'startLine' => 2116,
            'endLine' => 2116,
            'startColumn' => 71,
            'endColumn' => 76,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'field' => 
          array (
            'name' => 'field',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2116,
            'endLine' => 2116,
            'startColumn' => 79,
            'endColumn' => 84,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Retrieve the child model for a bound value.
 *
 * @param  string  $childType
 * @param  mixed  $value
 * @param  string|null  $field
 * @return \\Illuminate\\Database\\Eloquent\\Model|null
 */',
        'startLine' => 2116,
        'endLine' => 2119,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'resolveChildRouteBindingQuery' => 
      array (
        'name' => 'resolveChildRouteBindingQuery',
        'parameters' => 
        array (
          'childType' => 
          array (
            'name' => 'childType',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2129,
            'endLine' => 2129,
            'startColumn' => 54,
            'endColumn' => 63,
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
            'startLine' => 2129,
            'endLine' => 2129,
            'startColumn' => 66,
            'endColumn' => 71,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'field' => 
          array (
            'name' => 'field',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2129,
            'endLine' => 2129,
            'startColumn' => 74,
            'endColumn' => 79,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Retrieve the child model query for a bound value.
 *
 * @param  string  $childType
 * @param  mixed  $value
 * @param  string|null  $field
 * @return \\Illuminate\\Database\\Eloquent\\Relations\\Relation<\\Illuminate\\Database\\Eloquent\\Model, $this, *>
 */',
        'startLine' => 2129,
        'endLine' => 2143,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'childRouteBindingRelationshipName' => 
      array (
        'name' => 'childRouteBindingRelationshipName',
        'parameters' => 
        array (
          'childType' => 
          array (
            'name' => 'childType',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2151,
            'endLine' => 2151,
            'startColumn' => 58,
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
 * Retrieve the child route model binding relationship name for the given child type.
 *
 * @param  string  $childType
 * @return string
 */',
        'startLine' => 2151,
        'endLine' => 2154,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'resolveRouteBindingQuery' => 
      array (
        'name' => 'resolveRouteBindingQuery',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2164,
            'endLine' => 2164,
            'startColumn' => 46,
            'endColumn' => 51,
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
            'startLine' => 2164,
            'endLine' => 2164,
            'startColumn' => 54,
            'endColumn' => 59,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'field' => 
          array (
            'name' => 'field',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 2164,
                'endLine' => 2164,
                'startTokenPos' => 7687,
                'startFilePos' => 58819,
                'endTokenPos' => 7687,
                'endFilePos' => 58822,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2164,
            'endLine' => 2164,
            'startColumn' => 62,
            'endColumn' => 74,
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
 * Retrieve the model for a bound value.
 *
 * @param  \\Illuminate\\Database\\Eloquent\\Model|\\Illuminate\\Contracts\\Database\\Eloquent\\Builder|\\Illuminate\\Database\\Eloquent\\Relations\\Relation  $query
 * @param  mixed  $value
 * @param  string|null  $field
 * @return \\Illuminate\\Contracts\\Database\\Eloquent\\Builder
 */',
        'startLine' => 2164,
        'endLine' => 2167,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'getForeignKey' => 
      array (
        'name' => 'getForeignKey',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the default foreign key name for the model.
 *
 * @return string
 */',
        'startLine' => 2174,
        'endLine' => 2177,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'getPerPage' => 
      array (
        'name' => 'getPerPage',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the number of models to return per page.
 *
 * @return int
 */',
        'startLine' => 2184,
        'endLine' => 2187,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'setPerPage' => 
      array (
        'name' => 'setPerPage',
        'parameters' => 
        array (
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
            'startLine' => 2195,
            'endLine' => 2195,
            'startColumn' => 32,
            'endColumn' => 39,
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
 * Set the number of models to return per page.
 *
 * @param  int  $perPage
 * @return $this
 */',
        'startLine' => 2195,
        'endLine' => 2200,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'preventsLazyLoading' => 
      array (
        'name' => 'preventsLazyLoading',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if lazy loading is disabled.
 *
 * @return bool
 */',
        'startLine' => 2207,
        'endLine' => 2210,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'preventsSilentlyDiscardingAttributes' => 
      array (
        'name' => 'preventsSilentlyDiscardingAttributes',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if discarding guarded attribute fills is disabled.
 *
 * @return bool
 */',
        'startLine' => 2217,
        'endLine' => 2220,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'preventsAccessingMissingAttributes' => 
      array (
        'name' => 'preventsAccessingMissingAttributes',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if accessing missing attributes is disabled.
 *
 * @return bool
 */',
        'startLine' => 2227,
        'endLine' => 2230,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'broadcastChannelRoute' => 
      array (
        'name' => 'broadcastChannelRoute',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the broadcast channel route definition that is associated with the given entity.
 *
 * @return string
 */',
        'startLine' => 2237,
        'endLine' => 2240,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'broadcastChannel' => 
      array (
        'name' => 'broadcastChannel',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the broadcast channel name that is associated with the given entity.
 *
 * @return string
 */',
        'startLine' => 2247,
        'endLine' => 2250,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
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
            'startLine' => 2258,
            'endLine' => 2258,
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
 * Dynamically retrieve attributes on the model.
 *
 * @param  string  $key
 * @return mixed
 */',
        'startLine' => 2258,
        'endLine' => 2261,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      '__set' => 
      array (
        'name' => '__set',
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
            'startLine' => 2270,
            'endLine' => 2270,
            'startColumn' => 27,
            'endColumn' => 30,
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
            'startLine' => 2270,
            'endLine' => 2270,
            'startColumn' => 33,
            'endColumn' => 38,
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
 * Dynamically set attributes on the model.
 *
 * @param  string  $key
 * @param  mixed  $value
 * @return void
 */',
        'startLine' => 2270,
        'endLine' => 2273,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'offsetExists' => 
      array (
        'name' => 'offsetExists',
        'parameters' => 
        array (
          'offset' => 
          array (
            'name' => 'offset',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2281,
            'endLine' => 2281,
            'startColumn' => 34,
            'endColumn' => 40,
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
 * Determine if the given attribute exists.
 *
 * @param  mixed  $offset
 * @return bool
 */',
        'startLine' => 2281,
        'endLine' => 2292,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'offsetGet' => 
      array (
        'name' => 'offsetGet',
        'parameters' => 
        array (
          'offset' => 
          array (
            'name' => 'offset',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2300,
            'endLine' => 2300,
            'startColumn' => 31,
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
            'name' => 'mixed',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the value for a given offset.
 *
 * @param  mixed  $offset
 * @return mixed
 */',
        'startLine' => 2300,
        'endLine' => 2303,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'offsetSet' => 
      array (
        'name' => 'offsetSet',
        'parameters' => 
        array (
          'offset' => 
          array (
            'name' => 'offset',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2312,
            'endLine' => 2312,
            'startColumn' => 31,
            'endColumn' => 37,
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
            'startLine' => 2312,
            'endLine' => 2312,
            'startColumn' => 40,
            'endColumn' => 45,
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
 * Set the value for a given offset.
 *
 * @param  mixed  $offset
 * @param  mixed  $value
 * @return void
 */',
        'startLine' => 2312,
        'endLine' => 2315,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      'offsetUnset' => 
      array (
        'name' => 'offsetUnset',
        'parameters' => 
        array (
          'offset' => 
          array (
            'name' => 'offset',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2323,
            'endLine' => 2323,
            'startColumn' => 33,
            'endColumn' => 39,
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
 * Unset the value for a given offset.
 *
 * @param  mixed  $offset
 * @return void
 */',
        'startLine' => 2323,
        'endLine' => 2326,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      '__isset' => 
      array (
        'name' => '__isset',
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
            'startLine' => 2334,
            'endLine' => 2334,
            'startColumn' => 29,
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
 * Determine if an attribute or relation exists on the model.
 *
 * @param  string  $key
 * @return bool
 */',
        'startLine' => 2334,
        'endLine' => 2337,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      '__unset' => 
      array (
        'name' => '__unset',
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
            'startLine' => 2345,
            'endLine' => 2345,
            'startColumn' => 29,
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
 * Unset an attribute on the model.
 *
 * @param  string  $key
 * @return void
 */',
        'startLine' => 2345,
        'endLine' => 2348,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
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
            'startLine' => 2357,
            'endLine' => 2357,
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
            'startLine' => 2357,
            'endLine' => 2357,
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
 * Handle dynamic method calls into the model.
 *
 * @param  string  $method
 * @param  array  $parameters
 * @return mixed
 */',
        'startLine' => 2357,
        'endLine' => 2373,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      '__callStatic' => 
      array (
        'name' => '__callStatic',
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
            'startLine' => 2382,
            'endLine' => 2382,
            'startColumn' => 41,
            'endColumn' => 47,
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
            'startLine' => 2382,
            'endLine' => 2382,
            'startColumn' => 50,
            'endColumn' => 60,
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
 * Handle dynamic static method calls into the model.
 *
 * @param  string  $method
 * @param  array  $parameters
 * @return mixed
 */',
        'startLine' => 2382,
        'endLine' => 2385,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
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
 * Convert the model to its string representation.
 *
 * @return string
 */',
        'startLine' => 2392,
        'endLine' => 2397,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
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
                'startLine' => 2405,
                'endLine' => 2405,
                'startTokenPos' => 8484,
                'startFilePos' => 64561,
                'endTokenPos' => 8484,
                'endFilePos' => 64564,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2405,
            'endLine' => 2405,
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
 * Indicate that the object\'s string representation should be escaped when __toString is invoked.
 *
 * @param  bool  $escape
 * @return $this
 */',
        'startLine' => 2405,
        'endLine' => 2410,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      '__sleep' => 
      array (
        'name' => '__sleep',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Prepare the object for serialization.
 *
 * @return array
 */',
        'startLine' => 2417,
        'endLine' => 2425,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'aliasName' => NULL,
      ),
      '__wakeup' => 
      array (
        'name' => '__wakeup',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * When a model is being unserialized, check if it needs to be booted.
 *
 * @return void
 */',
        'startLine' => 2432,
        'endLine' => 2437,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Database\\Eloquent',
        'declaringClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'implementingClassName' => 'Illuminate\\Database\\Eloquent\\Model',
        'currentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
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