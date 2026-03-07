<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Content/Repositories/ContentRepository.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Content\Repositories\ContentRepository
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-16e52c1817ccaa63255fddcf4977d532b2cff0672e2ed4dcb53a67d4eaafba9d',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Content\\Repositories\\ContentRepository',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Content/Repositories/ContentRepository.php',
      ),
    ),
    'namespace' => 'Modules\\Content\\Repositories',
    'name' => 'Modules\\Content\\Repositories\\ContentRepository',
    'shortName' => 'ContentRepository',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @mixin AbstractRepository
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 13,
    'endLine' => 525,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'MicroweberPackages\\Repository\\Repositories\\AbstractRepository',
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
      'filterMethods' => 
      array (
        'declaringClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'implementingClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'name' => 'filterMethods',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'category\' => \'whereCategoryIds\', \'categories\' => \'whereCategoryIds\']',
          'attributes' => 
          array (
            'startLine' => 15,
            'endLine' => 18,
            'startTokenPos' => 40,
            'startFilePos' => 309,
            'endTokenPos' => 56,
            'endFilePos' => 401,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 15,
        'endLine' => 18,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'model' => 
      array (
        'declaringClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'implementingClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'name' => 'model',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\\Modules\\Content\\Models\\Content::class',
          'attributes' => 
          array (
            'startLine' => 25,
            'endLine' => 25,
            'startTokenPos' => 67,
            'startFilePos' => 503,
            'endTokenPos' => 69,
            'endFilePos' => 540,
          ),
        ),
        'docComment' => '/**
 * Specify Models class name
 *
 * @return string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 25,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 59,
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
      'getMedia' => 
      array (
        'name' => 'getMedia',
        'parameters' => 
        array (
          'contentId' => 
          array (
            'name' => 'contentId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 34,
            'endLine' => 34,
            'startColumn' => 30,
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
 * Find the media for content by contentId.
 *
 * @param mixed $contentId
 *
 * @return array
 */',
        'startLine' => 34,
        'endLine' => 45,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Modules\\Content\\Repositories',
        'declaringClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'implementingClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'currentClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'aliasName' => NULL,
      ),
      'getCategories' => 
      array (
        'name' => 'getCategories',
        'parameters' => 
        array (
          'id' => 
          array (
            'name' => 'id',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 55,
            'endLine' => 55,
            'startColumn' => 35,
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
 * Retrieve the categories associated with a given content ID.
 *
 * @param mixed $id The ID of the content to retrieve categories for.
 *
 * @return array An array of categories associated with the content ID.
 */',
        'startLine' => 55,
        'endLine' => 88,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Modules\\Content\\Repositories',
        'declaringClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'implementingClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'currentClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'aliasName' => NULL,
      ),
      'getContentDataValues' => 
      array (
        'name' => 'getContentDataValues',
        'parameters' => 
        array (
          'id' => 
          array (
            'name' => 'id',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'int',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 97,
            'endLine' => 97,
            'startColumn' => 42,
            'endColumn' => 48,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Find content data values by content id.
 *
 * @param int $id
 *
 * @return array
 */',
        'startLine' => 97,
        'endLine' => 115,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Modules\\Content\\Repositories',
        'declaringClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'implementingClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'currentClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'aliasName' => NULL,
      ),
      'getContentData' => 
      array (
        'name' => 'getContentData',
        'parameters' => 
        array (
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
            'startLine' => 124,
            'endLine' => 124,
            'startColumn' => 36,
            'endColumn' => 41,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Find content data by content id.
 *
 * @param mixed $id
 *
 * @return array
 */',
        'startLine' => 124,
        'endLine' => 150,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Modules\\Content\\Repositories',
        'declaringClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'implementingClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'currentClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'aliasName' => NULL,
      ),
      'getCustomFields' => 
      array (
        'name' => 'getCustomFields',
        'parameters' => 
        array (
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
            'startLine' => 160,
            'endLine' => 160,
            'startColumn' => 37,
            'endColumn' => 42,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get custom fields by relId.
 *
 * @param mixed $relId
 *
 * @return array
 */',
        'startLine' => 160,
        'endLine' => 196,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Modules\\Content\\Repositories',
        'declaringClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'implementingClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'currentClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'aliasName' => NULL,
      ),
      'getCustomFieldsByType' => 
      array (
        'name' => 'getCustomFieldsByType',
        'parameters' => 
        array (
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
            'startLine' => 206,
            'endLine' => 206,
            'startColumn' => 43,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
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
            'startLine' => 206,
            'endLine' => 206,
            'startColumn' => 51,
            'endColumn' => 55,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Retrieve custom fields of a specific type for a given relationship ID.
 *
 * @param int $relId The relationship ID.
 * @param string $type The type of custom fields to retrieve.
 *
 * @return array An array containing the custom fields of the specified type.
 */',
        'startLine' => 206,
        'endLine' => 222,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Content\\Repositories',
        'declaringClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'implementingClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'currentClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'aliasName' => NULL,
      ),
      'getRelatedContentIds' => 
      array (
        'name' => 'getRelatedContentIds',
        'parameters' => 
        array (
          'id' => 
          array (
            'name' => 'id',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 232,
            'endLine' => 232,
            'startColumn' => 42,
            'endColumn' => 44,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Find related content IDs by content ID.
 *
 * @param mixed $id
 *
 * @return array
 */',
        'startLine' => 232,
        'endLine' => 247,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Modules\\Content\\Repositories',
        'declaringClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'implementingClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'currentClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'aliasName' => NULL,
      ),
      'getEditField' => 
      array (
        'name' => 'getEditField',
        'parameters' => 
        array (
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
            'startLine' => 258,
            'endLine' => 258,
            'startColumn' => 34,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'rel_type' => 
          array (
            'name' => 'rel_type',
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
            'startColumn' => 42,
            'endColumn' => 50,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'rel_id' => 
          array (
            'name' => 'rel_id',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 258,
                'endLine' => 258,
                'startTokenPos' => 1221,
                'startFilePos' => 7123,
                'endTokenPos' => 1221,
                'endFilePos' => 7127,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 258,
            'endLine' => 258,
            'startColumn' => 53,
            'endColumn' => 67,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
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
                  'name' => 'bool',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'array',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the HTML for an editable field.
 *
 * @param string $field The field name.
 * @param string $rel_type The related type.
 * @param mixed $rel_id The related ID (optional).
 *
 * @return array|false The HTML for the editable field.
 */',
        'startLine' => 258,
        'endLine' => 294,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Modules\\Content\\Repositories',
        'declaringClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'implementingClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'currentClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'aliasName' => NULL,
      ),
      'tags' => 
      array (
        'name' => 'tags',
        'parameters' => 
        array (
          'contentId' => 
          array (
            'name' => 'contentId',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 303,
                'endLine' => 303,
                'startTokenPos' => 1530,
                'startFilePos' => 8801,
                'endTokenPos' => 1530,
                'endFilePos' => 8805,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 303,
            'endLine' => 303,
            'startColumn' => 26,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'returnFullTagsData' => 
          array (
            'name' => 'returnFullTagsData',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 303,
                'endLine' => 303,
                'startTokenPos' => 1537,
                'startFilePos' => 8830,
                'endTokenPos' => 1537,
                'endFilePos' => 8834,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 303,
            'endLine' => 303,
            'startColumn' => 46,
            'endColumn' => 72,
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
 * Retrieves the tags associated with the specified content.
 *
 * @param bool|int $contentId The ID of the content to retrieve tags for.
 * @param bool $returnFullTagsData Whether to retrieve the full tag data or just the tag names.
 * @return array|false An array of tags associated with the content, or false if there are no tags.
 */',
        'startLine' => 303,
        'endLine' => 329,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Modules\\Content\\Repositories',
        'declaringClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'implementingClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'currentClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'aliasName' => NULL,
      ),
      'getFirstShopPage' => 
      array (
        'name' => 'getFirstShopPage',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Retrieves the first shop page.
 *
 * @return array|null Returns an array containing the first shop page data if found, or null if not found.
 */',
        'startLine' => 336,
        'endLine' => 340,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Content\\Repositories',
        'declaringClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'implementingClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'currentClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'aliasName' => NULL,
      ),
      'getAllShopPages' => 
      array (
        'name' => 'getAllShopPages',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 342,
        'endLine' => 346,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Content\\Repositories',
        'declaringClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'implementingClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'currentClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'aliasName' => NULL,
      ),
      'getAllBlogPages' => 
      array (
        'name' => 'getAllBlogPages',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 348,
        'endLine' => 352,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Content\\Repositories',
        'declaringClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'implementingClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'currentClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'aliasName' => NULL,
      ),
      'getFirstBlogPage' => 
      array (
        'name' => 'getFirstBlogPage',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 354,
        'endLine' => 357,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Content\\Repositories',
        'declaringClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'implementingClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'currentClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'aliasName' => NULL,
      ),
      'getThumbnail' => 
      array (
        'name' => 'getThumbnail',
        'parameters' => 
        array (
          'contentId' => 
          array (
            'name' => 'contentId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 371,
            'endLine' => 371,
            'startColumn' => 34,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'width' => 
          array (
            'name' => 'width',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 371,
                'endLine' => 371,
                'startTokenPos' => 1822,
                'startFilePos' => 10929,
                'endTokenPos' => 1822,
                'endFilePos' => 10933,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 371,
            'endLine' => 371,
            'startColumn' => 46,
            'endColumn' => 59,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'height' => 
          array (
            'name' => 'height',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 371,
                'endLine' => 371,
                'startTokenPos' => 1829,
                'startFilePos' => 10946,
                'endTokenPos' => 1829,
                'endFilePos' => 10950,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 371,
            'endLine' => 371,
            'startColumn' => 62,
            'endColumn' => 76,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'crop' => 
          array (
            'name' => 'crop',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 371,
                'endLine' => 371,
                'startTokenPos' => 1836,
                'startFilePos' => 10961,
                'endTokenPos' => 1836,
                'endFilePos' => 10965,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 371,
            'endLine' => 371,
            'startColumn' => 79,
            'endColumn' => 91,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the filename of the first media related to the content item,
 * and return a thumbnail for it if possible.
 *
 * @param int $contentId The ID of the content item.
 * @param int|false $width Optional. The width of the thumbnail.
 * @param int|false $height Optional. The height of the thumbnail.
 * @param bool|string $crop Optional. Whether to crop the thumbnail.
 *
 * @return string The URL of the thumbnail, or a placeholder image if no media found.
 */',
        'startLine' => 371,
        'endLine' => 392,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Modules\\Content\\Repositories',
        'declaringClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'implementingClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'currentClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'aliasName' => NULL,
      ),
      'getParents' => 
      array (
        'name' => 'getParents',
        'parameters' => 
        array (
          'id' => 
          array (
            'name' => 'id',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 401,
            'endLine' => 401,
            'startColumn' => 32,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
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
                  'name' => 'false',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the parents of a given content ID.
 *
 * @param int $id The ID to retrieve the parents for.
 * @return array|false An array of parent IDs, or false if no parents are found.
 */',
        'startLine' => 401,
        'endLine' => 435,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Content\\Repositories',
        'declaringClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'implementingClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'currentClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'aliasName' => NULL,
      ),
      'getInheritedParent' => 
      array (
        'name' => 'getInheritedParent',
        'parameters' => 
        array (
          'id' => 
          array (
            'name' => 'id',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 447,
            'endLine' => 447,
            'startColumn' => 40,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
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
                  'name' => 'int',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'false',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the ID of the first parent content that has a layout template selected.
 *
 * This function is used to retrieve the ID of the parent content that has both
 * \'active_site_template\' and \'layout_file\' values set in the database field.
 *
 * @param int $id The ID of the content.
 * @return int|false The ID of the parent content that has a layout, or false if none found.
 */',
        'startLine' => 447,
        'endLine' => 462,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Content\\Repositories',
        'declaringClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'implementingClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'currentClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'aliasName' => NULL,
      ),
      'getChildren' => 
      array (
        'name' => 'getChildren',
        'parameters' => 
        array (
          'id' => 
          array (
            'name' => 'id',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 471,
                'endLine' => 471,
                'startTokenPos' => 2374,
                'startFilePos' => 14013,
                'endTokenPos' => 2374,
                'endFilePos' => 14013,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 471,
            'endLine' => 471,
            'startColumn' => 33,
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
 * Get the children of a given content.
 *
 * @param int $id The ID of the node to get the children of.
 * @return array|false The IDs of the children of the node or false if no children were found.
 */',
        'startLine' => 471,
        'endLine' => 495,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Content\\Repositories',
        'declaringClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'implementingClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'currentClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'aliasName' => NULL,
      ),
      'createDefaultShopPage' => 
      array (
        'name' => 'createDefaultShopPage',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 498,
        'endLine' => 509,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Content\\Repositories',
        'declaringClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'implementingClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'currentClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'aliasName' => NULL,
      ),
      'createDefaultBlogPage' => 
      array (
        'name' => 'createDefaultBlogPage',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 511,
        'endLine' => 522,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Content\\Repositories',
        'declaringClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'implementingClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
        'currentClassName' => 'Modules\\Content\\Repositories\\ContentRepository',
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