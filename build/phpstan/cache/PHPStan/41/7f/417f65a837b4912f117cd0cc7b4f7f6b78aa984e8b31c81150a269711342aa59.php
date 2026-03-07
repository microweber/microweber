<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Notifications/Messages/MailMessage.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Notifications\Messages\MailMessage
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-57b811a8acba916c8604d50e40fb47d7423fa9900a24fd2a396260dc367f91e8-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Notifications/Messages/MailMessage.php',
      ),
    ),
    'namespace' => 'Illuminate\\Notifications\\Messages',
    'name' => 'Illuminate\\Notifications\\Messages\\MailMessage',
    'shortName' => 'MailMessage',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 14,
    'endLine' => 422,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Notifications\\Messages\\SimpleMessage',
    'implementsClassNames' => 
    array (
      0 => 'Illuminate\\Contracts\\Support\\Renderable',
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Support\\Traits\\Conditionable',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'view' => 
      array (
        'declaringClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'implementingClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'name' => 'view',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The view to be rendered.
 *
 * @var array|string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 23,
        'endLine' => 23,
        'startColumn' => 5,
        'endColumn' => 17,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'viewData' => 
      array (
        'declaringClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'implementingClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'name' => 'viewData',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 30,
            'endLine' => 30,
            'startTokenPos' => 81,
            'startFilePos' => 652,
            'endTokenPos' => 82,
            'endFilePos' => 653,
          ),
        ),
        'docComment' => '/**
 * The view data for the message.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 30,
        'endLine' => 30,
        'startColumn' => 5,
        'endColumn' => 26,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'markdown' => 
      array (
        'declaringClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'implementingClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'name' => 'markdown',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'notifications::email\'',
          'attributes' => 
          array (
            'startLine' => 37,
            'endLine' => 37,
            'startTokenPos' => 93,
            'startFilePos' => 783,
            'endTokenPos' => 93,
            'endFilePos' => 804,
          ),
        ),
        'docComment' => '/**
 * The Markdown template to render (if applicable).
 *
 * @var string|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 37,
        'endLine' => 37,
        'startColumn' => 5,
        'endColumn' => 46,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'theme' => 
      array (
        'declaringClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'implementingClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'name' => 'theme',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The current theme being used when generating emails.
 *
 * @var string|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 44,
        'endLine' => 44,
        'startColumn' => 5,
        'endColumn' => 18,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'from' => 
      array (
        'declaringClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'implementingClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'name' => 'from',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 51,
            'endLine' => 51,
            'startTokenPos' => 111,
            'startFilePos' => 1042,
            'endTokenPos' => 112,
            'endFilePos' => 1043,
          ),
        ),
        'docComment' => '/**
 * The "from" information for the message.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 51,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 22,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'replyTo' => 
      array (
        'declaringClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'implementingClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'name' => 'replyTo',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 58,
            'endLine' => 58,
            'startTokenPos' => 123,
            'startFilePos' => 1161,
            'endTokenPos' => 124,
            'endFilePos' => 1162,
          ),
        ),
        'docComment' => '/**
 * The "reply to" information for the message.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 58,
        'endLine' => 58,
        'startColumn' => 5,
        'endColumn' => 25,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'cc' => 
      array (
        'declaringClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'implementingClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'name' => 'cc',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 65,
            'endLine' => 65,
            'startTokenPos' => 135,
            'startFilePos' => 1269,
            'endTokenPos' => 136,
            'endFilePos' => 1270,
          ),
        ),
        'docComment' => '/**
 * The "cc" information for the message.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 65,
        'endLine' => 65,
        'startColumn' => 5,
        'endColumn' => 20,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'bcc' => 
      array (
        'declaringClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'implementingClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'name' => 'bcc',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 72,
            'endLine' => 72,
            'startTokenPos' => 147,
            'startFilePos' => 1379,
            'endTokenPos' => 148,
            'endFilePos' => 1380,
          ),
        ),
        'docComment' => '/**
 * The "bcc" information for the message.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 72,
        'endLine' => 72,
        'startColumn' => 5,
        'endColumn' => 21,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'attachments' => 
      array (
        'declaringClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'implementingClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'name' => 'attachments',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 79,
            'endLine' => 79,
            'startTokenPos' => 159,
            'startFilePos' => 1491,
            'endTokenPos' => 160,
            'endFilePos' => 1492,
          ),
        ),
        'docComment' => '/**
 * The attachments for the message.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 79,
        'endLine' => 79,
        'startColumn' => 5,
        'endColumn' => 29,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'rawAttachments' => 
      array (
        'declaringClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'implementingClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'name' => 'rawAttachments',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 86,
            'endLine' => 86,
            'startTokenPos' => 171,
            'startFilePos' => 1610,
            'endTokenPos' => 172,
            'endFilePos' => 1611,
          ),
        ),
        'docComment' => '/**
 * The raw attachments for the message.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 86,
        'endLine' => 86,
        'startColumn' => 5,
        'endColumn' => 32,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'tags' => 
      array (
        'declaringClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'implementingClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'name' => 'tags',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 93,
            'endLine' => 93,
            'startTokenPos' => 183,
            'startFilePos' => 1708,
            'endTokenPos' => 184,
            'endFilePos' => 1709,
          ),
        ),
        'docComment' => '/**
 * The tags for the message.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 93,
        'endLine' => 93,
        'startColumn' => 5,
        'endColumn' => 22,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'metadata' => 
      array (
        'declaringClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'implementingClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'name' => 'metadata',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 100,
            'endLine' => 100,
            'startTokenPos' => 195,
            'startFilePos' => 1814,
            'endTokenPos' => 196,
            'endFilePos' => 1815,
          ),
        ),
        'docComment' => '/**
 * The metadata for the message.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 100,
        'endLine' => 100,
        'startColumn' => 5,
        'endColumn' => 26,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'priority' => 
      array (
        'declaringClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'implementingClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'name' => 'priority',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * Priority level of the message.
 *
 * @var int
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 107,
        'endLine' => 107,
        'startColumn' => 5,
        'endColumn' => 21,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'callbacks' => 
      array (
        'declaringClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'implementingClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'name' => 'callbacks',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 114,
            'endLine' => 114,
            'startTokenPos' => 214,
            'startFilePos' => 2022,
            'endTokenPos' => 215,
            'endFilePos' => 2023,
          ),
        ),
        'docComment' => '/**
 * The callbacks for the message.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 114,
        'endLine' => 114,
        'startColumn' => 5,
        'endColumn' => 27,
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
      'view' => 
      array (
        'name' => 'view',
        'parameters' => 
        array (
          'view' => 
          array (
            'name' => 'view',
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
            'startColumn' => 26,
            'endColumn' => 30,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'data' => 
          array (
            'name' => 'data',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 123,
                'endLine' => 123,
                'startTokenPos' => 235,
                'startFilePos' => 2222,
                'endTokenPos' => 236,
                'endFilePos' => 2223,
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
            'startLine' => 123,
            'endLine' => 123,
            'startColumn' => 33,
            'endColumn' => 48,
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
 * Set the view for the mail message.
 *
 * @param  array|string  $view
 * @param  array  $data
 * @return $this
 */',
        'startLine' => 123,
        'endLine' => 131,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Notifications\\Messages',
        'declaringClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'implementingClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'currentClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'aliasName' => NULL,
      ),
      'text' => 
      array (
        'name' => 'text',
        'parameters' => 
        array (
          'textView' => 
          array (
            'name' => 'textView',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 140,
            'endLine' => 140,
            'startColumn' => 26,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'data' => 
          array (
            'name' => 'data',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 140,
                'endLine' => 140,
                'startTokenPos' => 292,
                'startFilePos' => 2565,
                'endTokenPos' => 293,
                'endFilePos' => 2566,
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
            'startLine' => 140,
            'endLine' => 140,
            'startColumn' => 37,
            'endColumn' => 52,
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
 * Set the plain text view for the mail message.
 *
 * @param  string  $textView
 * @param  array  $data
 * @return $this
 */',
        'startLine' => 140,
        'endLine' => 146,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Notifications\\Messages',
        'declaringClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'implementingClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'currentClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'aliasName' => NULL,
      ),
      'markdown' => 
      array (
        'name' => 'markdown',
        'parameters' => 
        array (
          'view' => 
          array (
            'name' => 'view',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 155,
            'endLine' => 155,
            'startColumn' => 30,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'data' => 
          array (
            'name' => 'data',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 155,
                'endLine' => 155,
                'startTokenPos' => 372,
                'startFilePos' => 2960,
                'endTokenPos' => 373,
                'endFilePos' => 2961,
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
            'startLine' => 155,
            'endLine' => 155,
            'startColumn' => 37,
            'endColumn' => 52,
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
 * Set the Markdown template for the notification.
 *
 * @param  string  $view
 * @param  array  $data
 * @return $this
 */',
        'startLine' => 155,
        'endLine' => 163,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Notifications\\Messages',
        'declaringClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'implementingClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'currentClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'aliasName' => NULL,
      ),
      'template' => 
      array (
        'name' => 'template',
        'parameters' => 
        array (
          'template' => 
          array (
            'name' => 'template',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 171,
            'endLine' => 171,
            'startColumn' => 30,
            'endColumn' => 38,
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
 * Set the default markdown template.
 *
 * @param  string  $template
 * @return $this
 */',
        'startLine' => 171,
        'endLine' => 176,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Notifications\\Messages',
        'declaringClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'implementingClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'currentClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'aliasName' => NULL,
      ),
      'theme' => 
      array (
        'name' => 'theme',
        'parameters' => 
        array (
          'theme' => 
          array (
            'name' => 'theme',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 184,
            'endLine' => 184,
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
 * Set the theme to use with the Markdown template.
 *
 * @param  string  $theme
 * @return $this
 */',
        'startLine' => 184,
        'endLine' => 189,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Notifications\\Messages',
        'declaringClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'implementingClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'currentClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'aliasName' => NULL,
      ),
      'from' => 
      array (
        'name' => 'from',
        'parameters' => 
        array (
          'address' => 
          array (
            'name' => 'address',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 198,
            'endLine' => 198,
            'startColumn' => 26,
            'endColumn' => 33,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'name' => 
          array (
            'name' => 'name',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 198,
                'endLine' => 198,
                'startTokenPos' => 485,
                'startFilePos' => 3761,
                'endTokenPos' => 485,
                'endFilePos' => 3764,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 198,
            'endLine' => 198,
            'startColumn' => 36,
            'endColumn' => 47,
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
 * Set the from address for the mail message.
 *
 * @param  string  $address
 * @param  string|null  $name
 * @return $this
 */',
        'startLine' => 198,
        'endLine' => 203,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Notifications\\Messages',
        'declaringClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'implementingClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'currentClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'aliasName' => NULL,
      ),
      'replyTo' => 
      array (
        'name' => 'replyTo',
        'parameters' => 
        array (
          'address' => 
          array (
            'name' => 'address',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 212,
            'endLine' => 212,
            'startColumn' => 29,
            'endColumn' => 36,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'name' => 
          array (
            'name' => 'name',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 212,
                'endLine' => 212,
                'startTokenPos' => 526,
                'startFilePos' => 4056,
                'endTokenPos' => 526,
                'endFilePos' => 4059,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 212,
            'endLine' => 212,
            'startColumn' => 39,
            'endColumn' => 50,
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
 * Set the "reply to" address of the message.
 *
 * @param  array|string  $address
 * @param  string|null  $name
 * @return $this
 */',
        'startLine' => 212,
        'endLine' => 221,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Notifications\\Messages',
        'declaringClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'implementingClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'currentClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'aliasName' => NULL,
      ),
      'cc' => 
      array (
        'name' => 'cc',
        'parameters' => 
        array (
          'address' => 
          array (
            'name' => 'address',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 230,
            'endLine' => 230,
            'startColumn' => 24,
            'endColumn' => 31,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'name' => 
          array (
            'name' => 'name',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 230,
                'endLine' => 230,
                'startTokenPos' => 604,
                'startFilePos' => 4492,
                'endTokenPos' => 604,
                'endFilePos' => 4495,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 230,
            'endLine' => 230,
            'startColumn' => 34,
            'endColumn' => 45,
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
 * Set the cc address for the mail message.
 *
 * @param  array|string  $address
 * @param  string|null  $name
 * @return $this
 */',
        'startLine' => 230,
        'endLine' => 239,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Notifications\\Messages',
        'declaringClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'implementingClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'currentClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'aliasName' => NULL,
      ),
      'bcc' => 
      array (
        'name' => 'bcc',
        'parameters' => 
        array (
          'address' => 
          array (
            'name' => 'address',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 248,
            'endLine' => 248,
            'startColumn' => 25,
            'endColumn' => 32,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'name' => 
          array (
            'name' => 'name',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 248,
                'endLine' => 248,
                'startTokenPos' => 682,
                'startFilePos' => 4920,
                'endTokenPos' => 682,
                'endFilePos' => 4923,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 248,
            'endLine' => 248,
            'startColumn' => 35,
            'endColumn' => 46,
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
 * Set the bcc address for the mail message.
 *
 * @param  array|string  $address
 * @param  string|null  $name
 * @return $this
 */',
        'startLine' => 248,
        'endLine' => 257,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Notifications\\Messages',
        'declaringClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'implementingClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'currentClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'aliasName' => NULL,
      ),
      'attach' => 
      array (
        'name' => 'attach',
        'parameters' => 
        array (
          'file' => 
          array (
            'name' => 'file',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 266,
            'endLine' => 266,
            'startColumn' => 28,
            'endColumn' => 32,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'options' => 
          array (
            'name' => 'options',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 266,
                'endLine' => 266,
                'startTokenPos' => 762,
                'startFilePos' => 5401,
                'endTokenPos' => 763,
                'endFilePos' => 5402,
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
            'startLine' => 266,
            'endLine' => 266,
            'startColumn' => 35,
            'endColumn' => 53,
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
 * Attach a file to the message.
 *
 * @param  string|\\Illuminate\\Contracts\\Mail\\Attachable|\\Illuminate\\Mail\\Attachment  $file
 * @param  array  $options
 * @return $this
 */',
        'startLine' => 266,
        'endLine' => 279,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Notifications\\Messages',
        'declaringClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'implementingClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'currentClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'aliasName' => NULL,
      ),
      'attachMany' => 
      array (
        'name' => 'attachMany',
        'parameters' => 
        array (
          'files' => 
          array (
            'name' => 'files',
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
            'startColumn' => 32,
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
 * Attach multiple files to the message.
 *
 * @param  array<string|\\Illuminate\\Contracts\\Mail\\Attachable|\\Illuminate\\Mail\\Attachment|array>  $files
 * @return $this
 */',
        'startLine' => 287,
        'endLine' => 298,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Notifications\\Messages',
        'declaringClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'implementingClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'currentClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'aliasName' => NULL,
      ),
      'attachData' => 
      array (
        'name' => 'attachData',
        'parameters' => 
        array (
          'data' => 
          array (
            'name' => 'data',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 308,
            'endLine' => 308,
            'startColumn' => 32,
            'endColumn' => 36,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
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
            'startLine' => 308,
            'endLine' => 308,
            'startColumn' => 39,
            'endColumn' => 43,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'options' => 
          array (
            'name' => 'options',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 308,
                'endLine' => 308,
                'startTokenPos' => 937,
                'startFilePos' => 6428,
                'endTokenPos' => 938,
                'endFilePos' => 6429,
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
            'startLine' => 308,
            'endLine' => 308,
            'startColumn' => 46,
            'endColumn' => 64,
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
 * Attach in-memory data as an attachment.
 *
 * @param  string  $data
 * @param  string  $name
 * @param  array  $options
 * @return $this
 */',
        'startLine' => 308,
        'endLine' => 313,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Notifications\\Messages',
        'declaringClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'implementingClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'currentClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'aliasName' => NULL,
      ),
      'tag' => 
      array (
        'name' => 'tag',
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
            'startLine' => 321,
            'endLine' => 321,
            'startColumn' => 25,
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
 * Add a tag header to the message when supported by the underlying transport.
 *
 * @param  string  $value
 * @return $this
 */',
        'startLine' => 321,
        'endLine' => 326,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Notifications\\Messages',
        'declaringClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'implementingClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'currentClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'aliasName' => NULL,
      ),
      'metadata' => 
      array (
        'name' => 'metadata',
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
            'startLine' => 335,
            'endLine' => 335,
            'startColumn' => 30,
            'endColumn' => 33,
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
            'startLine' => 335,
            'endLine' => 335,
            'startColumn' => 36,
            'endColumn' => 41,
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
 * Add a metadata header to the message when supported by the underlying transport.
 *
 * @param  string  $key
 * @param  string  $value
 * @return $this
 */',
        'startLine' => 335,
        'endLine' => 340,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Notifications\\Messages',
        'declaringClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'implementingClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'currentClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'aliasName' => NULL,
      ),
      'priority' => 
      array (
        'name' => 'priority',
        'parameters' => 
        array (
          'level' => 
          array (
            'name' => 'level',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 350,
            'endLine' => 350,
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
 * Set the priority of this message.
 *
 * The value is an integer where 1 is the highest priority and 5 is the lowest.
 *
 * @param  int  $level
 * @return $this
 */',
        'startLine' => 350,
        'endLine' => 355,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Notifications\\Messages',
        'declaringClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'implementingClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'currentClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'aliasName' => NULL,
      ),
      'data' => 
      array (
        'name' => 'data',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the data array for the mail message.
 *
 * @return array
 */',
        'startLine' => 362,
        'endLine' => 365,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Notifications\\Messages',
        'declaringClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'implementingClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'currentClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'aliasName' => NULL,
      ),
      'parseAddresses' => 
      array (
        'name' => 'parseAddresses',
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
            'startLine' => 373,
            'endLine' => 373,
            'startColumn' => 39,
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
 * Parse the multi-address array into the necessary format.
 *
 * @param  array  $value
 * @return array
 */',
        'startLine' => 373,
        'endLine' => 378,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Notifications\\Messages',
        'declaringClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'implementingClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'currentClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'aliasName' => NULL,
      ),
      'arrayOfAddresses' => 
      array (
        'name' => 'arrayOfAddresses',
        'parameters' => 
        array (
          'address' => 
          array (
            'name' => 'address',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 386,
            'endLine' => 386,
            'startColumn' => 41,
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
 * Determine if the given "address" is actually an array of addresses.
 *
 * @param  mixed  $address
 * @return bool
 */',
        'startLine' => 386,
        'endLine' => 389,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Notifications\\Messages',
        'declaringClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'implementingClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'currentClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'aliasName' => NULL,
      ),
      'render' => 
      array (
        'name' => 'render',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Render the mail notification message into an HTML string.
 *
 * @return \\Illuminate\\Support\\HtmlString
 */',
        'startLine' => 396,
        'endLine' => 408,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Notifications\\Messages',
        'declaringClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'implementingClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'currentClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'aliasName' => NULL,
      ),
      'withSymfonyMessage' => 
      array (
        'name' => 'withSymfonyMessage',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 416,
            'endLine' => 416,
            'startColumn' => 40,
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
 * Register a callback to be called with the Symfony message instance.
 *
 * @param  callable  $callback
 * @return $this
 */',
        'startLine' => 416,
        'endLine' => 421,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Notifications\\Messages',
        'declaringClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'implementingClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
        'currentClassName' => 'Illuminate\\Notifications\\Messages\\MailMessage',
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