<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../rtconner/laravel-tagging/src/Taggable.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Conner\Tagging\Taggable
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-d9fe0921dd3eabbe865519f2a3618fda60573b9a764c33be184284317044f0a7-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Conner\\Tagging\\Taggable',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../rtconner/laravel-tagging/src/Taggable.php',
      ),
    ),
    'namespace' => 'Conner\\Tagging',
    'name' => 'Conner\\Tagging\\Taggable',
    'shortName' => 'Taggable',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @package Conner\\Tagging
 * @method static Builder withAllTags(array $tags)
 * @method static Builder withAnyTag(array $tags)
 * @method static Builder withoutTags(array $tags)
 * @property Collection|Tagged[] tagged
 * @property Collection|Tag[] tags
 * @property string[] tag_names
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 21,
    'endLine' => 411,
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
      'autoTagValue' => 
      array (
        'declaringClassName' => 'Conner\\Tagging\\Taggable',
        'implementingClassName' => 'Conner\\Tagging\\Taggable',
        'name' => 'autoTagValue',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * Temp storage for auto tag
 *
 * @var mixed
 * @access protected
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 29,
        'endLine' => 29,
        'startColumn' => 5,
        'endColumn' => 28,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'autoTagSet' => 
      array (
        'declaringClassName' => 'Conner\\Tagging\\Taggable',
        'implementingClassName' => 'Conner\\Tagging\\Taggable',
        'name' => 'autoTagSet',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 37,
            'endLine' => 37,
            'startTokenPos' => 60,
            'startFilePos' => 842,
            'endTokenPos' => 60,
            'endFilePos' => 846,
          ),
        ),
        'docComment' => '/**
 * Track if auto tag has been manually set
 *
 * @var boolean
 * @access protected
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 37,
        'endLine' => 37,
        'startColumn' => 5,
        'endColumn' => 34,
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
      'bootTaggable' => 
      array (
        'name' => 'bootTaggable',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Boot the soft taggable trait for a model.
 *
 * @return void
 */',
        'startLine' => 44,
        'endLine' => 55,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Conner\\Tagging',
        'declaringClassName' => 'Conner\\Tagging\\Taggable',
        'implementingClassName' => 'Conner\\Tagging\\Taggable',
        'currentClassName' => 'Conner\\Tagging\\Taggable',
        'aliasName' => NULL,
      ),
      'tagged' => 
      array (
        'name' => 'tagged',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Return collection of tagged rows related to the tagged model
 *
 * @return \\Illuminate\\Database\\Eloquent\\Collection
 * @access private
 */',
        'startLine' => 63,
        'endLine' => 67,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Conner\\Tagging',
        'declaringClassName' => 'Conner\\Tagging\\Taggable',
        'implementingClassName' => 'Conner\\Tagging\\Taggable',
        'currentClassName' => 'Conner\\Tagging\\Taggable',
        'aliasName' => NULL,
      ),
      'getTagsAttribute' => 
      array (
        'name' => 'getTagsAttribute',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Return collection of tags related to the tagged model
 * TODO : I\'m sure there is a faster way to build this, but
 * If anyone knows how to do that, me love you long time.
 *
 * @return \\Illuminate\\Database\\Eloquent\\Collection|Tagged[]
 */',
        'startLine' => 76,
        'endLine' => 81,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Conner\\Tagging',
        'declaringClassName' => 'Conner\\Tagging\\Taggable',
        'implementingClassName' => 'Conner\\Tagging\\Taggable',
        'currentClassName' => 'Conner\\Tagging\\Taggable',
        'aliasName' => NULL,
      ),
      'getTagNamesAttribute' => 
      array (
        'name' => 'getTagNamesAttribute',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the tag names via attribute, example $model->tag_names
 */',
        'startLine' => 86,
        'endLine' => 89,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Conner\\Tagging',
        'declaringClassName' => 'Conner\\Tagging\\Taggable',
        'implementingClassName' => 'Conner\\Tagging\\Taggable',
        'currentClassName' => 'Conner\\Tagging\\Taggable',
        'aliasName' => NULL,
      ),
      'addTags' => 
      array (
        'name' => 'addTags',
        'parameters' => 
        array (
          'tagNames' => 
          array (
            'name' => 'tagNames',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 96,
            'endLine' => 96,
            'startColumn' => 29,
            'endColumn' => 37,
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
 * Perform the action of tagging the model with the given string
 *
 * @param string|array $tagNames
 */',
        'startLine' => 96,
        'endLine' => 103,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Conner\\Tagging',
        'declaringClassName' => 'Conner\\Tagging\\Taggable',
        'implementingClassName' => 'Conner\\Tagging\\Taggable',
        'currentClassName' => 'Conner\\Tagging\\Taggable',
        'aliasName' => NULL,
      ),
      'tag' => 
      array (
        'name' => 'tag',
        'parameters' => 
        array (
          'tagNames' => 
          array (
            'name' => 'tagNames',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 110,
            'endLine' => 110,
            'startColumn' => 25,
            'endColumn' => 33,
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
 * Perform the action of tagging the model with the given string
 *
 * @param string|array $tagNames
 */',
        'startLine' => 110,
        'endLine' => 113,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Conner\\Tagging',
        'declaringClassName' => 'Conner\\Tagging\\Taggable',
        'implementingClassName' => 'Conner\\Tagging\\Taggable',
        'currentClassName' => 'Conner\\Tagging\\Taggable',
        'aliasName' => NULL,
      ),
      'tagNames' => 
      array (
        'name' => 'tagNames',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Return array of the tag names related to the current model
 *
 * @return array
 */',
        'startLine' => 120,
        'endLine' => 125,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Conner\\Tagging',
        'declaringClassName' => 'Conner\\Tagging\\Taggable',
        'implementingClassName' => 'Conner\\Tagging\\Taggable',
        'currentClassName' => 'Conner\\Tagging\\Taggable',
        'aliasName' => NULL,
      ),
      'tagSlugs' => 
      array (
        'name' => 'tagSlugs',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Return array of the tag slugs related to the current model
 *
 * @return array
 */',
        'startLine' => 132,
        'endLine' => 137,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Conner\\Tagging',
        'declaringClassName' => 'Conner\\Tagging\\Taggable',
        'implementingClassName' => 'Conner\\Tagging\\Taggable',
        'currentClassName' => 'Conner\\Tagging\\Taggable',
        'aliasName' => NULL,
      ),
      'untag' => 
      array (
        'name' => 'untag',
        'parameters' => 
        array (
          'tagNames' => 
          array (
            'name' => 'tagNames',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 144,
                'endLine' => 144,
                'startTokenPos' => 418,
                'startFilePos' => 3431,
                'endTokenPos' => 418,
                'endFilePos' => 3434,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 144,
            'endLine' => 144,
            'startColumn' => 27,
            'endColumn' => 42,
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
 * Remove the tag from this model
 *
 * @param string|array|null $tagNames (or null to remove all tags)
 */',
        'startLine' => 144,
        'endLine' => 159,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Conner\\Tagging',
        'declaringClassName' => 'Conner\\Tagging\\Taggable',
        'implementingClassName' => 'Conner\\Tagging\\Taggable',
        'currentClassName' => 'Conner\\Tagging\\Taggable',
        'aliasName' => NULL,
      ),
      'retag' => 
      array (
        'name' => 'retag',
        'parameters' => 
        array (
          'tagNames' => 
          array (
            'name' => 'tagNames',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 166,
            'endLine' => 166,
            'startColumn' => 27,
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
 * Replace the tags from this model
 *
 * @param string|array $tagNames
 */',
        'startLine' => 166,
        'endLine' => 179,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Conner\\Tagging',
        'declaringClassName' => 'Conner\\Tagging\\Taggable',
        'implementingClassName' => 'Conner\\Tagging\\Taggable',
        'currentClassName' => 'Conner\\Tagging\\Taggable',
        'aliasName' => NULL,
      ),
      'scopeWithAllTags' => 
      array (
        'name' => 'scopeWithAllTags',
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
            'startLine' => 189,
            'endLine' => 189,
            'startColumn' => 38,
            'endColumn' => 51,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'tagNames' => 
          array (
            'name' => 'tagNames',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 189,
            'endLine' => 189,
            'startColumn' => 54,
            'endColumn' => 62,
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
            'name' => 'Illuminate\\Database\\Eloquent\\Builder',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Filter model to subset with the given tags
 *
 * @param Builder $query
 * @param array|string $tagNames
 * @return Builder
 * @access private
 */',
        'startLine' => 189,
        'endLine' => 216,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Conner\\Tagging',
        'declaringClassName' => 'Conner\\Tagging\\Taggable',
        'implementingClassName' => 'Conner\\Tagging\\Taggable',
        'currentClassName' => 'Conner\\Tagging\\Taggable',
        'aliasName' => NULL,
      ),
      'scopeWithAnyTag' => 
      array (
        'name' => 'scopeWithAnyTag',
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
            'startLine' => 226,
            'endLine' => 226,
            'startColumn' => 37,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'tagNames' => 
          array (
            'name' => 'tagNames',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 226,
            'endLine' => 226,
            'startColumn' => 53,
            'endColumn' => 61,
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
            'name' => 'Illuminate\\Database\\Eloquent\\Builder',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Filter model to subset with the given tags
 *
 * @param Builder $query
 * @param array|string $tagNames
 * @return Builder
 * @access private
 */',
        'startLine' => 226,
        'endLine' => 231,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Conner\\Tagging',
        'declaringClassName' => 'Conner\\Tagging\\Taggable',
        'implementingClassName' => 'Conner\\Tagging\\Taggable',
        'currentClassName' => 'Conner\\Tagging\\Taggable',
        'aliasName' => NULL,
      ),
      'scopeWithoutTags' => 
      array (
        'name' => 'scopeWithoutTags',
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
            'startLine' => 241,
            'endLine' => 241,
            'startColumn' => 38,
            'endColumn' => 51,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'tagNames' => 
          array (
            'name' => 'tagNames',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 241,
            'endLine' => 241,
            'startColumn' => 54,
            'endColumn' => 62,
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
            'name' => 'Illuminate\\Database\\Eloquent\\Builder',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Filter model to subset without the given tags
 *
 * @param Builder $query
 * @param array|string $tagNames
 * @return Builder
 * @access private
 */',
        'startLine' => 241,
        'endLine' => 246,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Conner\\Tagging',
        'declaringClassName' => 'Conner\\Tagging\\Taggable',
        'implementingClassName' => 'Conner\\Tagging\\Taggable',
        'currentClassName' => 'Conner\\Tagging\\Taggable',
        'aliasName' => NULL,
      ),
      'addSingleTag' => 
      array (
        'name' => 'addSingleTag',
        'parameters' => 
        array (
          'tagName' => 
          array (
            'name' => 'tagName',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 253,
            'endLine' => 253,
            'startColumn' => 35,
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
 * Adds a single tag
 *
 * @param string $tagName
 */',
        'startLine' => 253,
        'endLine' => 280,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Conner\\Tagging',
        'declaringClassName' => 'Conner\\Tagging\\Taggable',
        'implementingClassName' => 'Conner\\Tagging\\Taggable',
        'currentClassName' => 'Conner\\Tagging\\Taggable',
        'aliasName' => NULL,
      ),
      'removeSingleTag' => 
      array (
        'name' => 'removeSingleTag',
        'parameters' => 
        array (
          'tagName' => 
          array (
            'name' => 'tagName',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 287,
            'endLine' => 287,
            'startColumn' => 38,
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
 * Removes a single tag
 *
 * @param $tagName string
 */',
        'startLine' => 287,
        'endLine' => 300,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Conner\\Tagging',
        'declaringClassName' => 'Conner\\Tagging\\Taggable',
        'implementingClassName' => 'Conner\\Tagging\\Taggable',
        'currentClassName' => 'Conner\\Tagging\\Taggable',
        'aliasName' => NULL,
      ),
      'existingTags' => 
      array (
        'name' => 'existingTags',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Collection',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Return an array of all of the tags that are in use by this model
 *
 * @return Collection|Tagged[]
 */',
        'startLine' => 307,
        'endLine' => 317,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Conner\\Tagging',
        'declaringClassName' => 'Conner\\Tagging\\Taggable',
        'implementingClassName' => 'Conner\\Tagging\\Taggable',
        'currentClassName' => 'Conner\\Tagging\\Taggable',
        'aliasName' => NULL,
      ),
      'existingTagsInGroups' => 
      array (
        'name' => 'existingTagsInGroups',
        'parameters' => 
        array (
          'groups' => 
          array (
            'name' => 'groups',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 324,
            'endLine' => 324,
            'startColumn' => 49,
            'endColumn' => 55,
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
            'name' => 'Illuminate\\Database\\Eloquent\\Collection',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Return an array of all of the tags that are in use by this model
 * @param array $groups
 * @return Collection|Tagged[]
 */',
        'startLine' => 324,
        'endLine' => 336,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Conner\\Tagging',
        'declaringClassName' => 'Conner\\Tagging\\Taggable',
        'implementingClassName' => 'Conner\\Tagging\\Taggable',
        'currentClassName' => 'Conner\\Tagging\\Taggable',
        'aliasName' => NULL,
      ),
      'untagOnDelete' => 
      array (
        'name' => 'untagOnDelete',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Should untag on delete
 */',
        'startLine' => 342,
        'endLine' => 347,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Conner\\Tagging',
        'declaringClassName' => 'Conner\\Tagging\\Taggable',
        'implementingClassName' => 'Conner\\Tagging\\Taggable',
        'currentClassName' => 'Conner\\Tagging\\Taggable',
        'aliasName' => NULL,
      ),
      'shouldDeleteUnused' => 
      array (
        'name' => 'shouldDeleteUnused',
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
        'docComment' => '/**
 * Delete tags that are not used anymore
 */',
        'startLine' => 352,
        'endLine' => 355,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Conner\\Tagging',
        'declaringClassName' => 'Conner\\Tagging\\Taggable',
        'implementingClassName' => 'Conner\\Tagging\\Taggable',
        'currentClassName' => 'Conner\\Tagging\\Taggable',
        'aliasName' => NULL,
      ),
      'setTagNamesAttribute' => 
      array (
        'name' => 'setTagNamesAttribute',
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
            'startLine' => 362,
            'endLine' => 362,
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
 * Set tag names to be set on save
 *
 * @param mixed $value Data for retag
 */',
        'startLine' => 362,
        'endLine' => 366,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Conner\\Tagging',
        'declaringClassName' => 'Conner\\Tagging\\Taggable',
        'implementingClassName' => 'Conner\\Tagging\\Taggable',
        'currentClassName' => 'Conner\\Tagging\\Taggable',
        'aliasName' => NULL,
      ),
      'autoTagPostSave' => 
      array (
        'name' => 'autoTagPostSave',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * AutoTag post-save hook
 *
 * Tags model based on data stored in tmp property, or untags if manually
 * set to false value
 */',
        'startLine' => 374,
        'endLine' => 383,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Conner\\Tagging',
        'declaringClassName' => 'Conner\\Tagging\\Taggable',
        'implementingClassName' => 'Conner\\Tagging\\Taggable',
        'currentClassName' => 'Conner\\Tagging\\Taggable',
        'aliasName' => NULL,
      ),
      'assembleTagsForScoping' => 
      array (
        'name' => 'assembleTagsForScoping',
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
            'startLine' => 385,
            'endLine' => 385,
            'startColumn' => 45,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'tagNames' => 
          array (
            'name' => 'tagNames',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 385,
            'endLine' => 385,
            'startColumn' => 53,
            'endColumn' => 61,
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
        'startLine' => 385,
        'endLine' => 409,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 4,
        'namespace' => 'Conner\\Tagging',
        'declaringClassName' => 'Conner\\Tagging\\Taggable',
        'implementingClassName' => 'Conner\\Tagging\\Taggable',
        'currentClassName' => 'Conner\\Tagging\\Taggable',
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