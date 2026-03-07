<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Tag/Traits/TaggableTrait.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Tag\Traits\TaggableTrait
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-eb531ea3cbfd2da75516a9877811af9ac0daa04df4d87a5132fd9a5af94d8d22',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Tag\\Traits\\TaggableTrait',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Tag/Traits/TaggableTrait.php',
      ),
    ),
    'namespace' => 'Modules\\Tag\\Traits',
    'name' => 'Modules\\Tag\\Traits\\TaggableTrait',
    'shortName' => 'TaggableTrait',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @package Modules\\Tag
 * @method static Builder withAllTags(array $tags)
 * @method static Builder withAnyTag(array $tags)
 * @method static Builder withoutTags(array $tags)
 * @property Collection|Tagged[] tagged
 * @property Collection|Tag[] tags
 * @property string[] tag_names
 * @method void addTags(string|array $tagNames) Add tags to the model
 * @method void tag(string|array $tagNames) Alias for addTags
 * @method array tagNames() Get the tag names related to the current model
 * @method array tagSlugs() Get the tag slugs related to the current model
 * @method void untag(string|array|null $tagNames = null) Remove tags from the model
 * @method void retag(string|array $tagNames) Replace the tags from the model
 * @method static Collection|Tagged[] existingTags() Get all tags in use by this model
 * @method static Collection|Tagged[] existingTagsInGroups(array $groups) Get all tags in use by this model in specific groups
 * /
 *
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 31,
    'endLine' => 151,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Conner\\Tagging\\Taggable',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      '_addTagsToContent' => 
      array (
        'declaringClassName' => 'Modules\\Tag\\Traits\\TaggableTrait',
        'implementingClassName' => 'Modules\\Tag\\Traits\\TaggableTrait',
        'name' => '_addTagsToContent',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 36,
            'endLine' => 36,
            'startTokenPos' => 55,
            'startFilePos' => 1266,
            'endTokenPos' => 56,
            'endFilePos' => 1267,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 36,
        'endLine' => 36,
        'startColumn' => 5,
        'endColumn' => 36,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      '_toSaveTags' => 
      array (
        'declaringClassName' => 'Modules\\Tag\\Traits\\TaggableTrait',
        'implementingClassName' => 'Modules\\Tag\\Traits\\TaggableTrait',
        'name' => '_toSaveTags',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 37,
            'endLine' => 37,
            'startTokenPos' => 65,
            'startFilePos' => 1297,
            'endTokenPos' => 65,
            'endFilePos' => 1301,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 37,
        'endLine' => 37,
        'startColumn' => 5,
        'endColumn' => 33,
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
      'initializeTaggableTrait' => 
      array (
        'name' => 'initializeTaggableTrait',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 39,
        'endLine' => 45,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Tag\\Traits',
        'declaringClassName' => 'Modules\\Tag\\Traits\\TaggableTrait',
        'implementingClassName' => 'Modules\\Tag\\Traits\\TaggableTrait',
        'currentClassName' => 'Modules\\Tag\\Traits\\TaggableTrait',
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
        'startLine' => 50,
        'endLine' => 53,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Tag\\Traits',
        'declaringClassName' => 'Modules\\Tag\\Traits\\TaggableTrait',
        'implementingClassName' => 'Modules\\Tag\\Traits\\TaggableTrait',
        'currentClassName' => 'Modules\\Tag\\Traits\\TaggableTrait',
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
        'startLine' => 58,
        'endLine' => 61,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Tag\\Traits',
        'declaringClassName' => 'Modules\\Tag\\Traits\\TaggableTrait',
        'implementingClassName' => 'Modules\\Tag\\Traits\\TaggableTrait',
        'currentClassName' => 'Modules\\Tag\\Traits\\TaggableTrait',
        'aliasName' => NULL,
      ),
      'bootTaggableTrait' => 
      array (
        'name' => 'bootTaggableTrait',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 63,
        'endLine' => 122,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Tag\\Traits',
        'declaringClassName' => 'Modules\\Tag\\Traits\\TaggableTrait',
        'implementingClassName' => 'Modules\\Tag\\Traits\\TaggableTrait',
        'currentClassName' => 'Modules\\Tag\\Traits\\TaggableTrait',
        'aliasName' => NULL,
      ),
      'getTagNamesAttribute' => 
      array (
        'name' => 'getTagNamesAttribute',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 124,
        'endLine' => 131,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Tag\\Traits',
        'declaringClassName' => 'Modules\\Tag\\Traits\\TaggableTrait',
        'implementingClassName' => 'Modules\\Tag\\Traits\\TaggableTrait',
        'currentClassName' => 'Modules\\Tag\\Traits\\TaggableTrait',
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
        'startLine' => 141,
        'endLine' => 149,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Tag\\Traits',
        'declaringClassName' => 'Modules\\Tag\\Traits\\TaggableTrait',
        'implementingClassName' => 'Modules\\Tag\\Traits\\TaggableTrait',
        'currentClassName' => 'Modules\\Tag\\Traits\\TaggableTrait',
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