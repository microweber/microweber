<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../rtconner/laravel-tagging/src/Model/Tag.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Conner\Tagging\Model\Tag
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-87383059b694b18a98b00894e61105dd99adc0ac169776ded35dc1f9d9d976fb-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Conner\\Tagging\\Model\\Tag',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../rtconner/laravel-tagging/src/Model/Tag.php',
      ),
    ),
    'namespace' => 'Conner\\Tagging\\Model',
    'name' => 'Conner\\Tagging\\Model\\Tag',
    'shortName' => 'Tag',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @package Conner\\Tagging\\Model
 * @property string id
 * @property string name
 * @property string slug
 * @property bool suggest
 * @property integer count
 * @property integer tag_group_id
 * @property TagGroup group
 * @property string description
 * @method static suggested()
 * @method static inGroup(string $group)
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 22,
    'endLine' => 155,
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
        'declaringClassName' => 'Conner\\Tagging\\Model\\Tag',
        'implementingClassName' => 'Conner\\Tagging\\Model\\Tag',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'tagging_tags\'',
          'attributes' => 
          array (
            'startLine' => 24,
            'endLine' => 24,
            'startTokenPos' => 40,
            'startFilePos' => 539,
            'endTokenPos' => 40,
            'endFilePos' => 552,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 24,
        'endLine' => 24,
        'startColumn' => 5,
        'endColumn' => 38,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'timestamps' => 
      array (
        'declaringClassName' => 'Conner\\Tagging\\Model\\Tag',
        'implementingClassName' => 'Conner\\Tagging\\Model\\Tag',
        'name' => 'timestamps',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 25,
            'endLine' => 25,
            'startTokenPos' => 49,
            'startFilePos' => 580,
            'endTokenPos' => 49,
            'endFilePos' => 584,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 25,
        'endLine' => 25,
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
        'declaringClassName' => 'Conner\\Tagging\\Model\\Tag',
        'implementingClassName' => 'Conner\\Tagging\\Model\\Tag',
        'name' => 'fillable',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'name\', \'description\']',
          'attributes' => 
          array (
            'startLine' => 26,
            'endLine' => 26,
            'startTokenPos' => 58,
            'startFilePos' => 610,
            'endTokenPos' => 63,
            'endFilePos' => 632,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 26,
        'endLine' => 26,
        'startColumn' => 5,
        'endColumn' => 47,
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
                'startLine' => 31,
                'endLine' => 31,
                'startTokenPos' => 80,
                'startFilePos' => 736,
                'endTokenPos' => 81,
                'endFilePos' => 737,
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
            'startLine' => 31,
            'endLine' => 31,
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
 * @param array $attributes
 */',
        'startLine' => 31,
        'endLine' => 36,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Conner\\Tagging\\Model',
        'declaringClassName' => 'Conner\\Tagging\\Model\\Tag',
        'implementingClassName' => 'Conner\\Tagging\\Model\\Tag',
        'currentClassName' => 'Conner\\Tagging\\Model\\Tag',
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
                'startLine' => 41,
                'endLine' => 41,
                'startTokenPos' => 122,
                'startFilePos' => 931,
                'endTokenPos' => 123,
                'endFilePos' => 932,
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
            'startLine' => 41,
            'endLine' => 41,
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
 * @inheritDoc
 */',
        'startLine' => 41,
        'endLine' => 50,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Conner\\Tagging\\Model',
        'declaringClassName' => 'Conner\\Tagging\\Model\\Tag',
        'implementingClassName' => 'Conner\\Tagging\\Model\\Tag',
        'currentClassName' => 'Conner\\Tagging\\Model\\Tag',
        'aliasName' => NULL,
      ),
      'setGroup' => 
      array (
        'name' => 'setGroup',
        'parameters' => 
        array (
          'group' => 
          array (
            'name' => 'group',
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
            'startLine' => 57,
            'endLine' => 57,
            'startColumn' => 30,
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
 * Tag group setter
 * @param string $group
 * @return Tag
 */',
        'startLine' => 57,
        'endLine' => 73,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Conner\\Tagging\\Model',
        'declaringClassName' => 'Conner\\Tagging\\Model\\Tag',
        'implementingClassName' => 'Conner\\Tagging\\Model\\Tag',
        'currentClassName' => 'Conner\\Tagging\\Model\\Tag',
        'aliasName' => NULL,
      ),
      'removeGroup' => 
      array (
        'name' => 'removeGroup',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Tag group remove
 * @return Tag
 */',
        'startLine' => 79,
        'endLine' => 85,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Conner\\Tagging\\Model',
        'declaringClassName' => 'Conner\\Tagging\\Model\\Tag',
        'implementingClassName' => 'Conner\\Tagging\\Model\\Tag',
        'currentClassName' => 'Conner\\Tagging\\Model\\Tag',
        'aliasName' => NULL,
      ),
      'isInGroup' => 
      array (
        'name' => 'isInGroup',
        'parameters' => 
        array (
          'groupName' => 
          array (
            'name' => 'groupName',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 92,
            'endLine' => 92,
            'startColumn' => 31,
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
 * Tag group helper function
 * @param string $groupName
 * @return bool
 */',
        'startLine' => 92,
        'endLine' => 99,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Conner\\Tagging\\Model',
        'declaringClassName' => 'Conner\\Tagging\\Model\\Tag',
        'implementingClassName' => 'Conner\\Tagging\\Model\\Tag',
        'currentClassName' => 'Conner\\Tagging\\Model\\Tag',
        'aliasName' => NULL,
      ),
      'group' => 
      array (
        'name' => 'group',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Tag group relationship
 */',
        'startLine' => 104,
        'endLine' => 107,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Conner\\Tagging\\Model',
        'declaringClassName' => 'Conner\\Tagging\\Model\\Tag',
        'implementingClassName' => 'Conner\\Tagging\\Model\\Tag',
        'currentClassName' => 'Conner\\Tagging\\Model\\Tag',
        'aliasName' => NULL,
      ),
      'scopeSuggested' => 
      array (
        'name' => 'scopeSuggested',
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
            'startLine' => 112,
            'endLine' => 112,
            'startColumn' => 36,
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
 * Get suggested tags
 */',
        'startLine' => 112,
        'endLine' => 115,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Conner\\Tagging\\Model',
        'declaringClassName' => 'Conner\\Tagging\\Model\\Tag',
        'implementingClassName' => 'Conner\\Tagging\\Model\\Tag',
        'currentClassName' => 'Conner\\Tagging\\Model\\Tag',
        'aliasName' => NULL,
      ),
      'scopeInGroup' => 
      array (
        'name' => 'scopeInGroup',
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
            'startLine' => 123,
            'endLine' => 123,
            'startColumn' => 34,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'groupName' => 
          array (
            'name' => 'groupName',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 123,
            'endLine' => 123,
            'startColumn' => 50,
            'endColumn' => 59,
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
 * Get suggested tags
 * @param Builder $query
 * @param $groupName
 * @return Builder
 */',
        'startLine' => 123,
        'endLine' => 130,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Conner\\Tagging\\Model',
        'declaringClassName' => 'Conner\\Tagging\\Model\\Tag',
        'implementingClassName' => 'Conner\\Tagging\\Model\\Tag',
        'currentClassName' => 'Conner\\Tagging\\Model\\Tag',
        'aliasName' => NULL,
      ),
      'setNameAttribute' => 
      array (
        'name' => 'setNameAttribute',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
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
            'startLine' => 137,
            'endLine' => 137,
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
 * Set the name of the tag : $tag->name = \'myname\';
 *
 * @param string $value
 */',
        'startLine' => 137,
        'endLine' => 140,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Conner\\Tagging\\Model',
        'declaringClassName' => 'Conner\\Tagging\\Model\\Tag',
        'implementingClassName' => 'Conner\\Tagging\\Model\\Tag',
        'currentClassName' => 'Conner\\Tagging\\Model\\Tag',
        'aliasName' => NULL,
      ),
      'deleteUnused' => 
      array (
        'name' => 'deleteUnused',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Look at the tags table and delete any tags that are no longer in use by any taggable database rows.
 * Does not delete tags where \'suggest\' value is true
 *
 * @return mixed
 */',
        'startLine' => 148,
        'endLine' => 154,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Conner\\Tagging\\Model',
        'declaringClassName' => 'Conner\\Tagging\\Model\\Tag',
        'implementingClassName' => 'Conner\\Tagging\\Model\\Tag',
        'currentClassName' => 'Conner\\Tagging\\Model\\Tag',
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