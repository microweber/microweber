<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/Modules/Comments/Models/Comment.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Modules\Comments\Models\Comment
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-9936e2370677bcb4545fcfeb1f3b7c9e7a3c71e8dd4461d1ae6f0e6863915b0f',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Modules\\Comments\\Models\\Comment',
        'filename' => '/home/headless/Documents/GitHub/microweber/Modules/Comments/Models/Comment.php',
      ),
    ),
    'namespace' => 'Modules\\Comments\\Models',
    'name' => 'Modules\\Comments\\Models\\Comment',
    'shortName' => 'Comment',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 10,
    'endLine' => 240,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Notifications\\Notifiable',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'table' => 
      array (
        'declaringClassName' => 'Modules\\Comments\\Models\\Comment',
        'implementingClassName' => 'Modules\\Comments\\Models\\Comment',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'comments\'',
          'attributes' => 
          array (
            'startLine' => 13,
            'endLine' => 13,
            'startTokenPos' => 48,
            'startFilePos' => 277,
            'endTokenPos' => 48,
            'endFilePos' => 286,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 13,
        'endLine' => 13,
        'startColumn' => 5,
        'endColumn' => 34,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'Modules\\Comments\\Models\\Comment',
        'implementingClassName' => 'Modules\\Comments\\Models\\Comment',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'comment_subject\', \'comment_name\', \'comment_email\', \'comment_website\', \'comment_body\', \'rel_type\', \'rel_id\', \'reply_to_comment_id\', \'is_moderated\', \'is_new\', \'is_spam\', \'user_ip\', \'session_id\', \'created_by\']',
          'attributes' => 
          array (
            'startLine' => 14,
            'endLine' => 29,
            'startTokenPos' => 57,
            'startFilePos' => 315,
            'endTokenPos' => 100,
            'endFilePos' => 640,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 14,
        'endLine' => 29,
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
        'declaringClassName' => 'Modules\\Comments\\Models\\Comment',
        'implementingClassName' => 'Modules\\Comments\\Models\\Comment',
        'name' => 'casts',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'rel_type\' => \'string\', \'rel_id\' => \'string\', \'reply_to_comment_id\' => \'integer\', \'is_moderated\' => \'boolean\', \'is_new\' => \'boolean\', \'is_spam\' => \'boolean\', \'is_reported\' => \'boolean\', \'created_by\' => \'integer\', \'user_ip\' => \'string\', \'session_id\' => \'string\', \'user_agent\' => \'string\', \'comment_body\' => \'string\', \'comment_name\' => \'string\', \'comment_email\' => \'string\', \'comment_website\' => \'string\', \'comment_subject\' => \'string\', \'created_at\' => \'datetime\', \'updated_at\' => \'datetime\']',
          'attributes' => 
          array (
            'startLine' => 31,
            'endLine' => 54,
            'startTokenPos' => 109,
            'startFilePos' => 667,
            'endTokenPos' => 237,
            'endFilePos' => 1312,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 31,
        'endLine' => 54,
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
      'isSpam' => 
      array (
        'name' => 'isSpam',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 56,
        'endLine' => 71,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Comments\\Models',
        'declaringClassName' => 'Modules\\Comments\\Models\\Comment',
        'implementingClassName' => 'Modules\\Comments\\Models\\Comment',
        'currentClassName' => 'Modules\\Comments\\Models\\Comment',
        'aliasName' => NULL,
      ),
      'shouldNotifyParent' => 
      array (
        'name' => 'shouldNotifyParent',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 73,
        'endLine' => 78,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Comments\\Models',
        'declaringClassName' => 'Modules\\Comments\\Models\\Comment',
        'implementingClassName' => 'Modules\\Comments\\Models\\Comment',
        'currentClassName' => 'Modules\\Comments\\Models\\Comment',
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
        'docComment' => NULL,
        'startLine' => 80,
        'endLine' => 92,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Modules\\Comments\\Models',
        'declaringClassName' => 'Modules\\Comments\\Models\\Comment',
        'implementingClassName' => 'Modules\\Comments\\Models\\Comment',
        'currentClassName' => 'Modules\\Comments\\Models\\Comment',
        'aliasName' => NULL,
      ),
      'commentBodyDisplay' => 
      array (
        'name' => 'commentBodyDisplay',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 94,
        'endLine' => 104,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Comments\\Models',
        'declaringClassName' => 'Modules\\Comments\\Models\\Comment',
        'implementingClassName' => 'Modules\\Comments\\Models\\Comment',
        'currentClassName' => 'Modules\\Comments\\Models\\Comment',
        'aliasName' => NULL,
      ),
      'isPending' => 
      array (
        'name' => 'isPending',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 106,
        'endLine' => 108,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Comments\\Models',
        'declaringClassName' => 'Modules\\Comments\\Models\\Comment',
        'implementingClassName' => 'Modules\\Comments\\Models\\Comment',
        'currentClassName' => 'Modules\\Comments\\Models\\Comment',
        'aliasName' => NULL,
      ),
      'scopePending' => 
      array (
        'name' => 'scopePending',
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
            'startLine' => 110,
            'endLine' => 110,
            'startColumn' => 34,
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
        'docComment' => NULL,
        'startLine' => 110,
        'endLine' => 113,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Comments\\Models',
        'declaringClassName' => 'Modules\\Comments\\Models\\Comment',
        'implementingClassName' => 'Modules\\Comments\\Models\\Comment',
        'currentClassName' => 'Modules\\Comments\\Models\\Comment',
        'aliasName' => NULL,
      ),
      'scopeApproved' => 
      array (
        'name' => 'scopeApproved',
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
            'startLine' => 115,
            'endLine' => 115,
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
        'docComment' => NULL,
        'startLine' => 115,
        'endLine' => 118,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Comments\\Models',
        'declaringClassName' => 'Modules\\Comments\\Models\\Comment',
        'implementingClassName' => 'Modules\\Comments\\Models\\Comment',
        'currentClassName' => 'Modules\\Comments\\Models\\Comment',
        'aliasName' => NULL,
      ),
      'scopeSpam' => 
      array (
        'name' => 'scopeSpam',
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
            'startLine' => 120,
            'endLine' => 120,
            'startColumn' => 31,
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
        'docComment' => NULL,
        'startLine' => 120,
        'endLine' => 123,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Comments\\Models',
        'declaringClassName' => 'Modules\\Comments\\Models\\Comment',
        'implementingClassName' => 'Modules\\Comments\\Models\\Comment',
        'currentClassName' => 'Modules\\Comments\\Models\\Comment',
        'aliasName' => NULL,
      ),
      'scopeForAdminPreview' => 
      array (
        'name' => 'scopeForAdminPreview',
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
            'startLine' => 125,
            'endLine' => 125,
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
        'docComment' => NULL,
        'startLine' => 125,
        'endLine' => 132,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Comments\\Models',
        'declaringClassName' => 'Modules\\Comments\\Models\\Comment',
        'implementingClassName' => 'Modules\\Comments\\Models\\Comment',
        'currentClassName' => 'Modules\\Comments\\Models\\Comment',
        'aliasName' => NULL,
      ),
      'scopePublished' => 
      array (
        'name' => 'scopePublished',
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
            'startLine' => 134,
            'endLine' => 134,
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
        'docComment' => NULL,
        'startLine' => 134,
        'endLine' => 142,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Comments\\Models',
        'declaringClassName' => 'Modules\\Comments\\Models\\Comment',
        'implementingClassName' => 'Modules\\Comments\\Models\\Comment',
        'currentClassName' => 'Modules\\Comments\\Models\\Comment',
        'aliasName' => NULL,
      ),
      'content' => 
      array (
        'name' => 'content',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 144,
        'endLine' => 147,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Comments\\Models',
        'declaringClassName' => 'Modules\\Comments\\Models\\Comment',
        'implementingClassName' => 'Modules\\Comments\\Models\\Comment',
        'currentClassName' => 'Modules\\Comments\\Models\\Comment',
        'aliasName' => NULL,
      ),
      'contentId' => 
      array (
        'name' => 'contentId',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 149,
        'endLine' => 155,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Comments\\Models',
        'declaringClassName' => 'Modules\\Comments\\Models\\Comment',
        'implementingClassName' => 'Modules\\Comments\\Models\\Comment',
        'currentClassName' => 'Modules\\Comments\\Models\\Comment',
        'aliasName' => NULL,
      ),
      'contentTitle' => 
      array (
        'name' => 'contentTitle',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 157,
        'endLine' => 160,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Comments\\Models',
        'declaringClassName' => 'Modules\\Comments\\Models\\Comment',
        'implementingClassName' => 'Modules\\Comments\\Models\\Comment',
        'currentClassName' => 'Modules\\Comments\\Models\\Comment',
        'aliasName' => NULL,
      ),
      'getCommentNameAttribute' => 
      array (
        'name' => 'getCommentNameAttribute',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 162,
        'endLine' => 171,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Comments\\Models',
        'declaringClassName' => 'Modules\\Comments\\Models\\Comment',
        'implementingClassName' => 'Modules\\Comments\\Models\\Comment',
        'currentClassName' => 'Modules\\Comments\\Models\\Comment',
        'aliasName' => NULL,
      ),
      'getCommentEmailAttribute' => 
      array (
        'name' => 'getCommentEmailAttribute',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 173,
        'endLine' => 182,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Comments\\Models',
        'declaringClassName' => 'Modules\\Comments\\Models\\Comment',
        'implementingClassName' => 'Modules\\Comments\\Models\\Comment',
        'currentClassName' => 'Modules\\Comments\\Models\\Comment',
        'aliasName' => NULL,
      ),
      'getAvatarUrl' => 
      array (
        'name' => 'getAvatarUrl',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 184,
        'endLine' => 187,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Comments\\Models',
        'declaringClassName' => 'Modules\\Comments\\Models\\Comment',
        'implementingClassName' => 'Modules\\Comments\\Models\\Comment',
        'currentClassName' => 'Modules\\Comments\\Models\\Comment',
        'aliasName' => NULL,
      ),
      'getCommentBodyAttribute' => 
      array (
        'name' => 'getCommentBodyAttribute',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 189,
        'endLine' => 192,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Comments\\Models',
        'declaringClassName' => 'Modules\\Comments\\Models\\Comment',
        'implementingClassName' => 'Modules\\Comments\\Models\\Comment',
        'currentClassName' => 'Modules\\Comments\\Models\\Comment',
        'aliasName' => NULL,
      ),
      'replies' => 
      array (
        'name' => 'replies',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 194,
        'endLine' => 197,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Comments\\Models',
        'declaringClassName' => 'Modules\\Comments\\Models\\Comment',
        'implementingClassName' => 'Modules\\Comments\\Models\\Comment',
        'currentClassName' => 'Modules\\Comments\\Models\\Comment',
        'aliasName' => NULL,
      ),
      'parent' => 
      array (
        'name' => 'parent',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 199,
        'endLine' => 202,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Comments\\Models',
        'declaringClassName' => 'Modules\\Comments\\Models\\Comment',
        'implementingClassName' => 'Modules\\Comments\\Models\\Comment',
        'currentClassName' => 'Modules\\Comments\\Models\\Comment',
        'aliasName' => NULL,
      ),
      'parentComment' => 
      array (
        'name' => 'parentComment',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 205,
        'endLine' => 208,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Comments\\Models',
        'declaringClassName' => 'Modules\\Comments\\Models\\Comment',
        'implementingClassName' => 'Modules\\Comments\\Models\\Comment',
        'currentClassName' => 'Modules\\Comments\\Models\\Comment',
        'aliasName' => NULL,
      ),
      'parentCommentBody' => 
      array (
        'name' => 'parentCommentBody',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 210,
        'endLine' => 215,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Comments\\Models',
        'declaringClassName' => 'Modules\\Comments\\Models\\Comment',
        'implementingClassName' => 'Modules\\Comments\\Models\\Comment',
        'currentClassName' => 'Modules\\Comments\\Models\\Comment',
        'aliasName' => NULL,
      ),
      'getLevel' => 
      array (
        'name' => 'getLevel',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 217,
        'endLine' => 228,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Comments\\Models',
        'declaringClassName' => 'Modules\\Comments\\Models\\Comment',
        'implementingClassName' => 'Modules\\Comments\\Models\\Comment',
        'currentClassName' => 'Modules\\Comments\\Models\\Comment',
        'aliasName' => NULL,
      ),
      'deleteWithReplies' => 
      array (
        'name' => 'deleteWithReplies',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 230,
        'endLine' => 238,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Modules\\Comments\\Models',
        'declaringClassName' => 'Modules\\Comments\\Models\\Comment',
        'implementingClassName' => 'Modules\\Comments\\Models\\Comment',
        'currentClassName' => 'Modules\\Comments\\Models\\Comment',
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