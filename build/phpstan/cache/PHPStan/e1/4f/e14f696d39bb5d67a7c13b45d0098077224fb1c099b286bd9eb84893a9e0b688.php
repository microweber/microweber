<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/SiteStats/Models/StatsEvent.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\SiteStats\Models\StatsEvent
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-c91e77696efcf4103892a2aac888cf7ce245e11c97928b18ffee49027bd770a3',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\SiteStats\\Models\\StatsEvent',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/SiteStats/Models/StatsEvent.php',
      ),
    ),
    'namespace' => 'Modules\\SiteStats\\Models',
    'name' => 'Modules\\SiteStats\\Models\\StatsEvent',
    'shortName' => 'StatsEvent',
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
    'endLine' => 74,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
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
      'table' => 
      array (
        'declaringClassName' => 'Modules\\SiteStats\\Models\\StatsEvent',
        'implementingClassName' => 'Modules\\SiteStats\\Models\\StatsEvent',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'stats_events\'',
          'attributes' => 
          array (
            'startLine' => 11,
            'endLine' => 11,
            'startTokenPos' => 37,
            'startFilePos' => 223,
            'endTokenPos' => 37,
            'endFilePos' => 236,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 11,
        'endLine' => 11,
        'startColumn' => 5,
        'endColumn' => 38,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'Modules\\SiteStats\\Models\\StatsEvent',
        'implementingClassName' => 'Modules\\SiteStats\\Models\\StatsEvent',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'event_category\', \'event_action\', \'event_label\', \'event_value\', \'user_id\', \'session_id\', \'utm_source\', \'utm_medium\', \'utm_campaign\', \'utm_term\', \'utm_content\', \'utm_visitor_id\', \'event_data\', \'event_timestamp\']',
          'attributes' => 
          array (
            'startLine' => 13,
            'endLine' => 28,
            'startTokenPos' => 46,
            'startFilePos' => 270,
            'endTokenPos' => 89,
            'endFilePos' => 599,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 13,
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
      'saveNewUtm' => 
      array (
        'name' => 'saveNewUtm',
        'parameters' => 
        array (
          'event' => 
          array (
            'name' => 'event',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Modules\\SiteStats\\DTO\\UtmEvent',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 30,
            'endLine' => 30,
            'startColumn' => 39,
            'endColumn' => 53,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 30,
        'endLine' => 72,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\SiteStats\\Models',
        'declaringClassName' => 'Modules\\SiteStats\\Models\\StatsEvent',
        'implementingClassName' => 'Modules\\SiteStats\\Models\\StatsEvent',
        'currentClassName' => 'Modules\\SiteStats\\Models\\StatsEvent',
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