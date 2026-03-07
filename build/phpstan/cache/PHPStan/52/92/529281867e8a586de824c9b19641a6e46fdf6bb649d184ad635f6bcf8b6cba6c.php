<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Media/Models/Media.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Media\Models\Media
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-4faef23686c802220cedfc3e37c792c39130e8ad14e13875b0c06ae18198bc7e',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Media\\Models\\Media',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Media/Models/Media.php',
      ),
    ),
    'namespace' => 'Modules\\Media\\Models',
    'name' => 'Modules\\Media\\Models\\Media',
    'shortName' => 'Media',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 9,
    'endLine' => 42,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'MicroweberPackages\\Database\\Traits\\MaxPositionTrait',
      1 => 'MicroweberPackages\\Database\\Traits\\CacheableQueryBuilderTrait',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'cacheTagsToClear' => 
      array (
        'declaringClassName' => 'Modules\\Media\\Models\\Media',
        'implementingClassName' => 'Modules\\Media\\Models\\Media',
        'name' => 'cacheTagsToClear',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'media\', \'media_thumbnails\']',
          'attributes' => 
          array (
            'startLine' => 12,
            'endLine' => 12,
            'startTokenPos' => 44,
            'startFilePos' => 357,
            'endTokenPos' => 48,
            'endFilePos' => 384,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 12,
        'endLine' => 12,
        'startColumn' => 5,
        'endColumn' => 60,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'table' => 
      array (
        'declaringClassName' => 'Modules\\Media\\Models\\Media',
        'implementingClassName' => 'Modules\\Media\\Models\\Media',
        'name' => 'table',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'media\'',
          'attributes' => 
          array (
            'startLine' => 17,
            'endLine' => 17,
            'startTokenPos' => 67,
            'startFilePos' => 471,
            'endTokenPos' => 67,
            'endFilePos' => 477,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 17,
        'endLine' => 17,
        'startColumn' => 5,
        'endColumn' => 28,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'Modules\\Media\\Models\\Media',
        'implementingClassName' => 'Modules\\Media\\Models\\Media',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'id\', \'title\', \'description\', \'rel_id\', \'rel_type\', \'media_type\', \'position\', \'filename\', \'session_id\', \'image_options\']',
          'attributes' => 
          array (
            'startLine' => 19,
            'endLine' => 30,
            'startTokenPos' => 76,
            'startFilePos' => 507,
            'endTokenPos' => 108,
            'endFilePos' => 714,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 19,
        'endLine' => 30,
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
        'declaringClassName' => 'Modules\\Media\\Models\\Media',
        'implementingClassName' => 'Modules\\Media\\Models\\Media',
        'name' => 'casts',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'image_options\' => \'json\', \'filename\' => \\MicroweberPackages\\Database\\Casts\\ReplaceSiteUrlCast::class]',
          'attributes' => 
          array (
            'startLine' => 33,
            'endLine' => 36,
            'startTokenPos' => 117,
            'startFilePos' => 742,
            'endTokenPos' => 137,
            'endFilePos' => 905,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 33,
        'endLine' => 36,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'attributes' => 
      array (
        'declaringClassName' => 'Modules\\Media\\Models\\Media',
        'implementingClassName' => 'Modules\\Media\\Models\\Media',
        'name' => 'attributes',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'media_type\' => \'picture\']',
          'attributes' => 
          array (
            'startLine' => 38,
            'endLine' => 40,
            'startTokenPos' => 146,
            'startFilePos' => 937,
            'endTokenPos' => 155,
            'endFilePos' => 978,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 38,
        'endLine' => 40,
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