<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Rating/Models/Rating.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Rating\Models\Rating
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-77195fcc8f8508ea0dee4afde0524e183d496ede006f6d54ed5291c57c638053',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Rating\\Models\\Rating',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Rating/Models/Rating.php',
      ),
    ),
    'namespace' => 'Modules\\Rating\\Models',
    'name' => 'Modules\\Rating\\Models\\Rating',
    'shortName' => 'Rating',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 8,
    'endLine' => 56,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'MicroweberPackages\\Database\\Traits\\CacheableQueryBuilderTrait',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'table' => 
      array (
        'declaringClassName' => 'Modules\\Rating\\Models\\Rating',
        'implementingClassName' => 'Modules\\Rating\\Models\\Rating',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'rating\'',
          'attributes' => 
          array (
            'startLine' => 12,
            'endLine' => 12,
            'startTokenPos' => 38,
            'startFilePos' => 238,
            'endTokenPos' => 38,
            'endFilePos' => 245,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 12,
        'endLine' => 12,
        'startColumn' => 5,
        'endColumn' => 32,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'Modules\\Rating\\Models\\Rating',
        'implementingClassName' => 'Modules\\Rating\\Models\\Rating',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'rel_type\', \'rel_id\', \'rating\', \'comment\', \'session_id\', \'created_by\', \'edited_by\']',
          'attributes' => 
          array (
            'startLine' => 14,
            'endLine' => 22,
            'startTokenPos' => 47,
            'startFilePos' => 275,
            'endTokenPos' => 69,
            'endFilePos' => 420,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 14,
        'endLine' => 22,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'casts' => 
      array (
        'declaringClassName' => 'Modules\\Rating\\Models\\Rating',
        'implementingClassName' => 'Modules\\Rating\\Models\\Rating',
        'name' => 'casts',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'rating\' => \'integer\', \'created_at\' => \'datetime\', \'updated_at\' => \'datetime\']',
          'attributes' => 
          array (
            'startLine' => 24,
            'endLine' => 28,
            'startTokenPos' => 78,
            'startFilePos' => 447,
            'endTokenPos' => 100,
            'endFilePos' => 555,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 24,
        'endLine' => 28,
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
        'docComment' => NULL,
        'startLine' => 30,
        'endLine' => 45,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'Modules\\Rating\\Models',
        'declaringClassName' => 'Modules\\Rating\\Models\\Rating',
        'implementingClassName' => 'Modules\\Rating\\Models\\Rating',
        'currentClassName' => 'Modules\\Rating\\Models\\Rating',
        'aliasName' => NULL,
      ),
      'getAverage' => 
      array (
        'name' => 'getAverage',
        'parameters' => 
        array (
          'relType' => 
          array (
            'name' => 'relType',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 47,
            'endLine' => 47,
            'startColumn' => 39,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'relId' => 
          array (
            'name' => 'relId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 47,
            'endLine' => 47,
            'startColumn' => 49,
            'endColumn' => 54,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 47,
        'endLine' => 55,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Rating\\Models',
        'declaringClassName' => 'Modules\\Rating\\Models\\Rating',
        'implementingClassName' => 'Modules\\Rating\\Models\\Rating',
        'currentClassName' => 'Modules\\Rating\\Models\\Rating',
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