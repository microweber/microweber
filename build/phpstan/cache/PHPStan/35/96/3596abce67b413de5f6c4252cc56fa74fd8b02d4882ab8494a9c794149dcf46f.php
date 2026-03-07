<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../rtconner/laravel-tagging/src/Model/Tagged.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Conner\Tagging\Model\Tagged
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-a563e2c8ff309728016db3c28a2e50ee5f8805f2c704a7136408e7ca3ac02cad-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Conner\\Tagging\\Model\\Tagged',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../rtconner/laravel-tagging/src/Model/Tagged.php',
      ),
    ),
    'namespace' => 'Conner\\Tagging\\Model',
    'name' => 'Conner\\Tagging\\Model\\Tagged',
    'shortName' => 'Tagged',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @package Conner\\Tagging\\Model
 *
 * @property integer id
 * @property string taggable_id
 * @property string taggable_type
 * @property string tag_name
 * @property string tag_slug
 * @property Tag tag
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 16,
    'endLine' => 50,
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
        'declaringClassName' => 'Conner\\Tagging\\Model\\Tagged',
        'implementingClassName' => 'Conner\\Tagging\\Model\\Tagged',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'tagging_tagged\'',
          'attributes' => 
          array (
            'startLine' => 18,
            'endLine' => 18,
            'startTokenPos' => 34,
            'startFilePos' => 380,
            'endTokenPos' => 34,
            'endFilePos' => 395,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 18,
        'endLine' => 18,
        'startColumn' => 5,
        'endColumn' => 40,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'timestamps' => 
      array (
        'declaringClassName' => 'Conner\\Tagging\\Model\\Tagged',
        'implementingClassName' => 'Conner\\Tagging\\Model\\Tagged',
        'name' => 'timestamps',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 19,
            'endLine' => 19,
            'startTokenPos' => 43,
            'startFilePos' => 423,
            'endTokenPos' => 43,
            'endFilePos' => 427,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 19,
        'endLine' => 19,
        'startColumn' => 5,
        'endColumn' => 31,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'Conner\\Tagging\\Model\\Tagged',
        'implementingClassName' => 'Conner\\Tagging\\Model\\Tagged',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'tag_name\', \'tag_slug\']',
          'attributes' => 
          array (
            'startLine' => 20,
            'endLine' => 20,
            'startTokenPos' => 52,
            'startFilePos' => 456,
            'endTokenPos' => 57,
            'endFilePos' => 479,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 20,
        'endLine' => 20,
        'startColumn' => 5,
        'endColumn' => 51,
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
                'startLine' => 22,
                'endLine' => 22,
                'startTokenPos' => 72,
                'startFilePos' => 535,
                'endTokenPos' => 73,
                'endFilePos' => 536,
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
            'startLine' => 22,
            'endLine' => 22,
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
        'docComment' => NULL,
        'startLine' => 22,
        'endLine' => 27,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Conner\\Tagging\\Model',
        'declaringClassName' => 'Conner\\Tagging\\Model\\Tagged',
        'implementingClassName' => 'Conner\\Tagging\\Model\\Tagged',
        'currentClassName' => 'Conner\\Tagging\\Model\\Tagged',
        'aliasName' => NULL,
      ),
      'taggable' => 
      array (
        'name' => 'taggable',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Morph to the tag
 *
 * @return \\Illuminate\\Database\\Eloquent\\Relations\\MorphTo
 */',
        'startLine' => 34,
        'endLine' => 37,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Conner\\Tagging\\Model',
        'declaringClassName' => 'Conner\\Tagging\\Model\\Tagged',
        'implementingClassName' => 'Conner\\Tagging\\Model\\Tagged',
        'currentClassName' => 'Conner\\Tagging\\Model\\Tagged',
        'aliasName' => NULL,
      ),
      'tag' => 
      array (
        'name' => 'tag',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get instance of tag linked to the tagged value
 *
 * @return \\Illuminate\\Database\\Eloquent\\Relations\\BelongsTo
 */',
        'startLine' => 44,
        'endLine' => 48,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Conner\\Tagging\\Model',
        'declaringClassName' => 'Conner\\Tagging\\Model\\Tagged',
        'implementingClassName' => 'Conner\\Tagging\\Model\\Tagged',
        'currentClassName' => 'Conner\\Tagging\\Model\\Tagged',
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