<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Mail/Mailable.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Mail\Mailable
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-4bdcdddef21a885500213ad5b5d126bac239768920d4ad8c5977aa09aa0d197d-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Mail\\Mailable',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Mail/Mailable.php',
      ),
    ),
    'namespace' => 'Illuminate\\Mail',
    'name' => 'Illuminate\\Mail\\Mailable',
    'shortName' => 'Mailable',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 32,
    'endLine' => 1836,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'Illuminate\\Contracts\\Mail\\Mailable',
      1 => 'Illuminate\\Contracts\\Support\\Renderable',
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Support\\Traits\\Conditionable',
      1 => 'Illuminate\\Support\\Traits\\ForwardsCalls',
      2 => 'Illuminate\\Support\\Traits\\Localizable',
      3 => 'Illuminate\\Support\\Traits\\Tappable',
      4 => 'Illuminate\\Support\\Traits\\Macroable',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'locale' => 
      array (
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'name' => 'locale',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The locale of the message.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 43,
        'endLine' => 43,
        'startColumn' => 5,
        'endColumn' => 19,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'from' => 
      array (
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'name' => 'from',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 50,
            'endLine' => 50,
            'startTokenPos' => 216,
            'startFilePos' => 1527,
            'endTokenPos' => 217,
            'endFilePos' => 1528,
          ),
        ),
        'docComment' => '/**
 * The person the message is from.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 50,
        'endLine' => 50,
        'startColumn' => 5,
        'endColumn' => 22,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'to' => 
      array (
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'name' => 'to',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 57,
            'endLine' => 57,
            'startTokenPos' => 228,
            'startFilePos' => 1633,
            'endTokenPos' => 229,
            'endFilePos' => 1634,
          ),
        ),
        'docComment' => '/**
 * The "to" recipients of the message.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 57,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 20,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'cc' => 
      array (
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'name' => 'cc',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 64,
            'endLine' => 64,
            'startTokenPos' => 240,
            'startFilePos' => 1739,
            'endTokenPos' => 241,
            'endFilePos' => 1740,
          ),
        ),
        'docComment' => '/**
 * The "cc" recipients of the message.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 64,
        'endLine' => 64,
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
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'name' => 'bcc',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 71,
            'endLine' => 71,
            'startTokenPos' => 252,
            'startFilePos' => 1847,
            'endTokenPos' => 253,
            'endFilePos' => 1848,
          ),
        ),
        'docComment' => '/**
 * The "bcc" recipients of the message.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 71,
        'endLine' => 71,
        'startColumn' => 5,
        'endColumn' => 21,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'replyTo' => 
      array (
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'name' => 'replyTo',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 78,
            'endLine' => 78,
            'startTokenPos' => 264,
            'startFilePos' => 1964,
            'endTokenPos' => 265,
            'endFilePos' => 1965,
          ),
        ),
        'docComment' => '/**
 * The "reply to" recipients of the message.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 78,
        'endLine' => 78,
        'startColumn' => 5,
        'endColumn' => 25,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'subject' => 
      array (
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'name' => 'subject',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The subject of the message.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 85,
        'endLine' => 85,
        'startColumn' => 5,
        'endColumn' => 20,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'markdown' => 
      array (
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'name' => 'markdown',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The Markdown template for the message (if applicable).
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 92,
        'endLine' => 92,
        'startColumn' => 5,
        'endColumn' => 21,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'html' => 
      array (
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'name' => 'html',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The HTML to use for the message.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 99,
        'endLine' => 99,
        'startColumn' => 5,
        'endColumn' => 20,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'view' => 
      array (
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'name' => 'view',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The view to use for the message.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 106,
        'endLine' => 106,
        'startColumn' => 5,
        'endColumn' => 17,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'textView' => 
      array (
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'name' => 'textView',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The plain text view to use for the message.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 113,
        'endLine' => 113,
        'startColumn' => 5,
        'endColumn' => 21,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'viewData' => 
      array (
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'name' => 'viewData',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 120,
            'endLine' => 120,
            'startTokenPos' => 311,
            'startFilePos' => 2618,
            'endTokenPos' => 312,
            'endFilePos' => 2619,
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
        'startLine' => 120,
        'endLine' => 120,
        'startColumn' => 5,
        'endColumn' => 26,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'attachments' => 
      array (
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'name' => 'attachments',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 127,
            'endLine' => 127,
            'startTokenPos' => 323,
            'startFilePos' => 2730,
            'endTokenPos' => 324,
            'endFilePos' => 2731,
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
        'startLine' => 127,
        'endLine' => 127,
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
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'name' => 'rawAttachments',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 134,
            'endLine' => 134,
            'startTokenPos' => 335,
            'startFilePos' => 2849,
            'endTokenPos' => 336,
            'endFilePos' => 2850,
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
        'startLine' => 134,
        'endLine' => 134,
        'startColumn' => 5,
        'endColumn' => 32,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'diskAttachments' => 
      array (
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'name' => 'diskAttachments',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 141,
            'endLine' => 141,
            'startTokenPos' => 347,
            'startFilePos' => 2969,
            'endTokenPos' => 348,
            'endFilePos' => 2970,
          ),
        ),
        'docComment' => '/**
 * The attachments from a storage disk.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 141,
        'endLine' => 141,
        'startColumn' => 5,
        'endColumn' => 33,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'tags' => 
      array (
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'name' => 'tags',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 148,
            'endLine' => 148,
            'startTokenPos' => 359,
            'startFilePos' => 3070,
            'endTokenPos' => 360,
            'endFilePos' => 3071,
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
        'startLine' => 148,
        'endLine' => 148,
        'startColumn' => 5,
        'endColumn' => 25,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'metadata' => 
      array (
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'name' => 'metadata',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 155,
            'endLine' => 155,
            'startTokenPos' => 371,
            'startFilePos' => 3179,
            'endTokenPos' => 372,
            'endFilePos' => 3180,
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
        'startLine' => 155,
        'endLine' => 155,
        'startColumn' => 5,
        'endColumn' => 29,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'callbacks' => 
      array (
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'name' => 'callbacks',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 162,
            'endLine' => 162,
            'startTokenPos' => 383,
            'startFilePos' => 3287,
            'endTokenPos' => 384,
            'endFilePos' => 3288,
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
        'startLine' => 162,
        'endLine' => 162,
        'startColumn' => 5,
        'endColumn' => 27,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'theme' => 
      array (
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'name' => 'theme',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The name of the theme that should be used when formatting the message.
 *
 * @var string|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 169,
        'endLine' => 169,
        'startColumn' => 5,
        'endColumn' => 18,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'mailer' => 
      array (
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'name' => 'mailer',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The name of the mailer that should send the message.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 176,
        'endLine' => 176,
        'startColumn' => 5,
        'endColumn' => 19,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'assertionableRenderStrings' => 
      array (
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'name' => 'assertionableRenderStrings',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The rendered mailable views for testing / assertions.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 183,
        'endLine' => 183,
        'startColumn' => 5,
        'endColumn' => 42,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'viewDataCallback' => 
      array (
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'name' => 'viewDataCallback',
        'modifiers' => 17,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The callback that should be invoked while building the view data.
 *
 * @var callable
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 190,
        'endLine' => 190,
        'startColumn' => 5,
        'endColumn' => 36,
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
      'send' => 
      array (
        'name' => 'send',
        'parameters' => 
        array (
          'mailer' => 
          array (
            'name' => 'mailer',
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
 * Send the message using the given mailer.
 *
 * @param  \\Illuminate\\Contracts\\Mail\\Factory|\\Illuminate\\Contracts\\Mail\\Mailer  $mailer
 * @return \\Illuminate\\Mail\\SentMessage|null
 */',
        'startLine' => 198,
        'endLine' => 217,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'queue' => 
      array (
        'name' => 'queue',
        'parameters' => 
        array (
          'queue' => 
          array (
            'name' => 'queue',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Contracts\\Queue\\Factory',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 225,
            'endLine' => 225,
            'startColumn' => 27,
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
 * Queue the message for sending.
 *
 * @param  \\Illuminate\\Contracts\\Queue\\Factory  $queue
 * @return mixed
 */',
        'startLine' => 225,
        'endLine' => 238,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'later' => 
      array (
        'name' => 'later',
        'parameters' => 
        array (
          'delay' => 
          array (
            'name' => 'delay',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 247,
            'endLine' => 247,
            'startColumn' => 27,
            'endColumn' => 32,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'queue' => 
          array (
            'name' => 'queue',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Contracts\\Queue\\Factory',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 247,
            'endLine' => 247,
            'startColumn' => 35,
            'endColumn' => 46,
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
 * Deliver the queued message after (n) seconds.
 *
 * @param  \\DateTimeInterface|\\DateInterval|int  $delay
 * @param  \\Illuminate\\Contracts\\Queue\\Factory  $queue
 * @return mixed
 */',
        'startLine' => 247,
        'endLine' => 256,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'newQueuedJob' => 
      array (
        'name' => 'newQueuedJob',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Make the queued mailable job instance.
 *
 * @return mixed
 */',
        'startLine' => 263,
        'endLine' => 270,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
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
 * Render the mailable into a view.
 *
 * @return string
 *
 * @throws \\ReflectionException
 */',
        'startLine' => 279,
        'endLine' => 288,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'buildView' => 
      array (
        'name' => 'buildView',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Build the view for the message.
 *
 * @return array|string
 *
 * @throws \\ReflectionException
 */',
        'startLine' => 297,
        'endLine' => 317,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'buildMarkdownView' => 
      array (
        'name' => 'buildMarkdownView',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Build the Markdown view for the message.
 *
 * @return array
 *
 * @throws \\ReflectionException
 */',
        'startLine' => 326,
        'endLine' => 334,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'buildViewData' => 
      array (
        'name' => 'buildViewData',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Build the view data for the message.
 *
 * @return array
 *
 * @throws \\ReflectionException
 */',
        'startLine' => 343,
        'endLine' => 358,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'additionalMessageData' => 
      array (
        'name' => 'additionalMessageData',
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
 * Get additional meta-data to pass along with the view data.
 *
 * @return array<string, mixed>
 */',
        'startLine' => 365,
        'endLine' => 370,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'buildMarkdownHtml' => 
      array (
        'name' => 'buildMarkdownHtml',
        'parameters' => 
        array (
          'viewData' => 
          array (
            'name' => 'viewData',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 378,
            'endLine' => 378,
            'startColumn' => 42,
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
 * Build the HTML view for a Markdown message.
 *
 * @param  array  $viewData
 * @return \\Closure
 */',
        'startLine' => 378,
        'endLine' => 384,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'buildMarkdownText' => 
      array (
        'name' => 'buildMarkdownText',
        'parameters' => 
        array (
          'viewData' => 
          array (
            'name' => 'viewData',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 392,
            'endLine' => 392,
            'startColumn' => 42,
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
 * Build the text view for a Markdown message.
 *
 * @param  array  $viewData
 * @return \\Closure
 */',
        'startLine' => 392,
        'endLine' => 406,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'markdownRenderer' => 
      array (
        'name' => 'markdownRenderer',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Resolves a Markdown instance with the mail\'s theme.
 *
 * @return \\Illuminate\\Mail\\Markdown
 */',
        'startLine' => 413,
        'endLine' => 420,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'buildFrom' => 
      array (
        'name' => 'buildFrom',
        'parameters' => 
        array (
          'message' => 
          array (
            'name' => 'message',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 428,
            'endLine' => 428,
            'startColumn' => 34,
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
 * Add the sender to the message.
 *
 * @param  \\Illuminate\\Mail\\Message  $message
 * @return $this
 */',
        'startLine' => 428,
        'endLine' => 435,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'buildRecipients' => 
      array (
        'name' => 'buildRecipients',
        'parameters' => 
        array (
          'message' => 
          array (
            'name' => 'message',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 443,
            'endLine' => 443,
            'startColumn' => 40,
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
 * Add all of the recipients to the message.
 *
 * @param  \\Illuminate\\Mail\\Message  $message
 * @return $this
 */',
        'startLine' => 443,
        'endLine' => 452,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'buildSubject' => 
      array (
        'name' => 'buildSubject',
        'parameters' => 
        array (
          'message' => 
          array (
            'name' => 'message',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 460,
            'endLine' => 460,
            'startColumn' => 37,
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
 * Set the subject for the message.
 *
 * @param  \\Illuminate\\Mail\\Message  $message
 * @return $this
 */',
        'startLine' => 460,
        'endLine' => 469,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'buildAttachments' => 
      array (
        'name' => 'buildAttachments',
        'parameters' => 
        array (
          'message' => 
          array (
            'name' => 'message',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 477,
            'endLine' => 477,
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
 * Add all of the attachments to the message.
 *
 * @param  \\Illuminate\\Mail\\Message  $message
 * @return $this
 */',
        'startLine' => 477,
        'endLine' => 492,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'buildDiskAttachments' => 
      array (
        'name' => 'buildDiskAttachments',
        'parameters' => 
        array (
          'message' => 
          array (
            'name' => 'message',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 500,
            'endLine' => 500,
            'startColumn' => 45,
            'endColumn' => 52,
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
 * Add all of the disk attachments to the message.
 *
 * @param  \\Illuminate\\Mail\\Message  $message
 * @return void
 */',
        'startLine' => 500,
        'endLine' => 513,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'buildTags' => 
      array (
        'name' => 'buildTags',
        'parameters' => 
        array (
          'message' => 
          array (
            'name' => 'message',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 521,
            'endLine' => 521,
            'startColumn' => 34,
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
 * Add all defined tags to the message.
 *
 * @param  \\Illuminate\\Mail\\Message  $message
 * @return $this
 */',
        'startLine' => 521,
        'endLine' => 530,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'buildMetadata' => 
      array (
        'name' => 'buildMetadata',
        'parameters' => 
        array (
          'message' => 
          array (
            'name' => 'message',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 538,
            'endLine' => 538,
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
 * Add all defined metadata to the message.
 *
 * @param  \\Illuminate\\Mail\\Message  $message
 * @return $this
 */',
        'startLine' => 538,
        'endLine' => 547,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'runCallbacks' => 
      array (
        'name' => 'runCallbacks',
        'parameters' => 
        array (
          'message' => 
          array (
            'name' => 'message',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 555,
            'endLine' => 555,
            'startColumn' => 37,
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
 * Run the callbacks for the message.
 *
 * @param  \\Illuminate\\Mail\\Message  $message
 * @return $this
 */',
        'startLine' => 555,
        'endLine' => 562,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'locale' => 
      array (
        'name' => 'locale',
        'parameters' => 
        array (
          'locale' => 
          array (
            'name' => 'locale',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 570,
            'endLine' => 570,
            'startColumn' => 28,
            'endColumn' => 34,
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
 * Set the locale of the message.
 *
 * @param  string  $locale
 * @return $this
 */',
        'startLine' => 570,
        'endLine' => 575,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
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
            'default' => 
            array (
              'code' => '3',
              'attributes' => 
              array (
                'startLine' => 585,
                'endLine' => 585,
                'startTokenPos' => 2250,
                'startFilePos' => 14434,
                'endTokenPos' => 2250,
                'endFilePos' => 14434,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 585,
            'endLine' => 585,
            'startColumn' => 30,
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
 * Set the priority of this message.
 *
 * The value is an integer where 1 is the highest priority and 5 is the lowest.
 *
 * @param  int  $level
 * @return $this
 */',
        'startLine' => 585,
        'endLine' => 592,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
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
            'startLine' => 601,
            'endLine' => 601,
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
                'startLine' => 601,
                'endLine' => 601,
                'startTokenPos' => 2310,
                'startFilePos' => 14792,
                'endTokenPos' => 2310,
                'endFilePos' => 14795,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 601,
            'endLine' => 601,
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
 * Set the sender of the message.
 *
 * @param  object|array|string  $address
 * @param  string|null  $name
 * @return $this
 */',
        'startLine' => 601,
        'endLine' => 604,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'hasFrom' => 
      array (
        'name' => 'hasFrom',
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
            'startLine' => 613,
            'endLine' => 613,
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
                'startLine' => 613,
                'endLine' => 613,
                'startTokenPos' => 2348,
                'startFilePos' => 15102,
                'endTokenPos' => 2348,
                'endFilePos' => 15105,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 613,
            'endLine' => 613,
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
 * Determine if the given recipient is set on the mailable.
 *
 * @param  object|array|string  $address
 * @param  string|null  $name
 * @return bool
 */',
        'startLine' => 613,
        'endLine' => 616,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'to' => 
      array (
        'name' => 'to',
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
            'startLine' => 625,
            'endLine' => 625,
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
                'startLine' => 625,
                'endLine' => 625,
                'startTokenPos' => 2386,
                'startFilePos' => 15388,
                'endTokenPos' => 2386,
                'endFilePos' => 15391,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 625,
            'endLine' => 625,
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
 * Set the recipients of the message.
 *
 * @param  object|array|string  $address
 * @param  string|null  $name
 * @return $this
 */',
        'startLine' => 625,
        'endLine' => 632,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'hasTo' => 
      array (
        'name' => 'hasTo',
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
            'startLine' => 641,
            'endLine' => 641,
            'startColumn' => 27,
            'endColumn' => 34,
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
                'startLine' => 641,
                'endLine' => 641,
                'startTokenPos' => 2458,
                'startFilePos' => 15835,
                'endTokenPos' => 2458,
                'endFilePos' => 15838,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 641,
            'endLine' => 641,
            'startColumn' => 37,
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
 * Determine if the given recipient is set on the mailable.
 *
 * @param  object|array|string  $address
 * @param  string|null  $name
 * @return bool
 */',
        'startLine' => 641,
        'endLine' => 644,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
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
            'startLine' => 653,
            'endLine' => 653,
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
                'startLine' => 653,
                'endLine' => 653,
                'startTokenPos' => 2496,
                'startFilePos' => 16119,
                'endTokenPos' => 2496,
                'endFilePos' => 16122,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 653,
            'endLine' => 653,
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
 * Set the recipients of the message.
 *
 * @param  object|array|string  $address
 * @param  string|null  $name
 * @return $this
 */',
        'startLine' => 653,
        'endLine' => 656,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'hasCc' => 
      array (
        'name' => 'hasCc',
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
            'startLine' => 665,
            'endLine' => 665,
            'startColumn' => 27,
            'endColumn' => 34,
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
                'startLine' => 665,
                'endLine' => 665,
                'startTokenPos' => 2534,
                'startFilePos' => 16425,
                'endTokenPos' => 2534,
                'endFilePos' => 16428,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 665,
            'endLine' => 665,
            'startColumn' => 37,
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
 * Determine if the given recipient is set on the mailable.
 *
 * @param  object|array|string  $address
 * @param  string|null  $name
 * @return bool
 */',
        'startLine' => 665,
        'endLine' => 668,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
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
            'startLine' => 677,
            'endLine' => 677,
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
                'startLine' => 677,
                'endLine' => 677,
                'startTokenPos' => 2572,
                'startFilePos' => 16710,
                'endTokenPos' => 2572,
                'endFilePos' => 16713,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 677,
            'endLine' => 677,
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
 * Set the recipients of the message.
 *
 * @param  object|array|string  $address
 * @param  string|null  $name
 * @return $this
 */',
        'startLine' => 677,
        'endLine' => 680,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'hasBcc' => 
      array (
        'name' => 'hasBcc',
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
            'startLine' => 689,
            'endLine' => 689,
            'startColumn' => 28,
            'endColumn' => 35,
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
                'startLine' => 689,
                'endLine' => 689,
                'startTokenPos' => 2610,
                'startFilePos' => 17018,
                'endTokenPos' => 2610,
                'endFilePos' => 17021,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 689,
            'endLine' => 689,
            'startColumn' => 38,
            'endColumn' => 49,
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
 * Determine if the given recipient is set on the mailable.
 *
 * @param  object|array|string  $address
 * @param  string|null  $name
 * @return bool
 */',
        'startLine' => 689,
        'endLine' => 692,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
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
            'startLine' => 701,
            'endLine' => 701,
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
                'startLine' => 701,
                'endLine' => 701,
                'startTokenPos' => 2648,
                'startFilePos' => 17316,
                'endTokenPos' => 2648,
                'endFilePos' => 17319,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 701,
            'endLine' => 701,
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
 * @param  object|array|string  $address
 * @param  string|null  $name
 * @return $this
 */',
        'startLine' => 701,
        'endLine' => 704,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'hasReplyTo' => 
      array (
        'name' => 'hasReplyTo',
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
            'startLine' => 713,
            'endLine' => 713,
            'startColumn' => 32,
            'endColumn' => 39,
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
                'startLine' => 713,
                'endLine' => 713,
                'startTokenPos' => 2686,
                'startFilePos' => 17630,
                'endTokenPos' => 2686,
                'endFilePos' => 17633,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 713,
            'endLine' => 713,
            'startColumn' => 42,
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
 * Determine if the given replyTo is set on the mailable.
 *
 * @param  object|array|string  $address
 * @param  string|null  $name
 * @return bool
 */',
        'startLine' => 713,
        'endLine' => 716,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'setAddress' => 
      array (
        'name' => 'setAddress',
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
            'startLine' => 728,
            'endLine' => 728,
            'startColumn' => 35,
            'endColumn' => 42,
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
                'startLine' => 728,
                'endLine' => 728,
                'startTokenPos' => 2724,
                'startFilePos' => 18049,
                'endTokenPos' => 2724,
                'endFilePos' => 18052,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 728,
            'endLine' => 728,
            'startColumn' => 45,
            'endColumn' => 56,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'property' => 
          array (
            'name' => 'property',
            'default' => 
            array (
              'code' => '\'to\'',
              'attributes' => 
              array (
                'startLine' => 728,
                'endLine' => 728,
                'startTokenPos' => 2731,
                'startFilePos' => 18067,
                'endTokenPos' => 2731,
                'endFilePos' => 18070,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 728,
            'endLine' => 728,
            'startColumn' => 59,
            'endColumn' => 74,
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
 * Set the recipients of the message.
 *
 * All recipients are stored internally as [[\'name\' => ?, \'address\' => ?]]
 *
 * @param  object|array|string  $address
 * @param  string|null  $name
 * @param  string  $property
 * @return $this
 */',
        'startLine' => 728,
        'endLine' => 751,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'addressesToArray' => 
      array (
        'name' => 'addressesToArray',
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
            'startLine' => 760,
            'endLine' => 760,
            'startColumn' => 41,
            'endColumn' => 48,
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
            'startLine' => 760,
            'endLine' => 760,
            'startColumn' => 51,
            'endColumn' => 55,
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
 * Convert the given recipient arguments to an array.
 *
 * @param  object|array|string  $address
 * @param  string|null  $name
 * @return array
 */',
        'startLine' => 760,
        'endLine' => 767,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'normalizeRecipient' => 
      array (
        'name' => 'normalizeRecipient',
        'parameters' => 
        array (
          'recipient' => 
          array (
            'name' => 'recipient',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 775,
            'endLine' => 775,
            'startColumn' => 43,
            'endColumn' => 52,
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
 * Convert the given recipient into an object.
 *
 * @param  mixed  $recipient
 * @return object
 */',
        'startLine' => 775,
        'endLine' => 794,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'hasRecipient' => 
      array (
        'name' => 'hasRecipient',
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
            'startLine' => 804,
            'endLine' => 804,
            'startColumn' => 37,
            'endColumn' => 44,
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
                'startLine' => 804,
                'endLine' => 804,
                'startTokenPos' => 3170,
                'startFilePos' => 20320,
                'endTokenPos' => 3170,
                'endFilePos' => 20323,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 804,
            'endLine' => 804,
            'startColumn' => 47,
            'endColumn' => 58,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'property' => 
          array (
            'name' => 'property',
            'default' => 
            array (
              'code' => '\'to\'',
              'attributes' => 
              array (
                'startLine' => 804,
                'endLine' => 804,
                'startTokenPos' => 3177,
                'startFilePos' => 20338,
                'endTokenPos' => 3177,
                'endFilePos' => 20341,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 804,
            'endLine' => 804,
            'startColumn' => 61,
            'endColumn' => 76,
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
 * Determine if the given recipient is set on the mailable.
 *
 * @param  object|array|string  $address
 * @param  string|null  $name
 * @param  string  $property
 * @return bool
 */',
        'startLine' => 804,
        'endLine' => 830,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'hasEnvelopeRecipient' => 
      array (
        'name' => 'hasEnvelopeRecipient',
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
            'startLine' => 840,
            'endLine' => 840,
            'startColumn' => 43,
            'endColumn' => 50,
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
            'startLine' => 840,
            'endLine' => 840,
            'startColumn' => 53,
            'endColumn' => 57,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'property' => 
          array (
            'name' => 'property',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 840,
            'endLine' => 840,
            'startColumn' => 60,
            'endColumn' => 68,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the mailable "envelope" method defines a recipient.
 *
 * @param  string  $address
 * @param  string|null  $name
 * @param  string  $property
 * @return bool
 */',
        'startLine' => 840,
        'endLine' => 849,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'subject' => 
      array (
        'name' => 'subject',
        'parameters' => 
        array (
          'subject' => 
          array (
            'name' => 'subject',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 857,
            'endLine' => 857,
            'startColumn' => 29,
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
        'docComment' => '/**
 * Set the subject of the message.
 *
 * @param  string  $subject
 * @return $this
 */',
        'startLine' => 857,
        'endLine' => 862,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'hasSubject' => 
      array (
        'name' => 'hasSubject',
        'parameters' => 
        array (
          'subject' => 
          array (
            'name' => 'subject',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 870,
            'endLine' => 870,
            'startColumn' => 32,
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
 * Determine if the mailable has the given subject.
 *
 * @param  string  $subject
 * @return bool
 */',
        'startLine' => 870,
        'endLine' => 874,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
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
            'startLine' => 883,
            'endLine' => 883,
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
                'startLine' => 883,
                'endLine' => 883,
                'startTokenPos' => 3603,
                'startFilePos' => 22534,
                'endTokenPos' => 3604,
                'endFilePos' => 22535,
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
            'startLine' => 883,
            'endLine' => 883,
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
 * Set the Markdown template for the message.
 *
 * @param  string  $view
 * @param  array  $data
 * @return $this
 */',
        'startLine' => 883,
        'endLine' => 889,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
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
            'startLine' => 898,
            'endLine' => 898,
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
                'startLine' => 898,
                'endLine' => 898,
                'startTokenPos' => 3659,
                'startFilePos' => 22868,
                'endTokenPos' => 3660,
                'endFilePos' => 22869,
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
            'startLine' => 898,
            'endLine' => 898,
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
 * Set the view and view data for the message.
 *
 * @param  string  $view
 * @param  array  $data
 * @return $this
 */',
        'startLine' => 898,
        'endLine' => 904,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'html' => 
      array (
        'name' => 'html',
        'parameters' => 
        array (
          'html' => 
          array (
            'name' => 'html',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 912,
            'endLine' => 912,
            'startColumn' => 26,
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
 * Set the rendered HTML content for the message.
 *
 * @param  string  $html
 * @return $this
 */',
        'startLine' => 912,
        'endLine' => 917,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
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
            'startLine' => 926,
            'endLine' => 926,
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
                'startLine' => 926,
                'endLine' => 926,
                'startTokenPos' => 3744,
                'startFilePos' => 23427,
                'endTokenPos' => 3745,
                'endFilePos' => 23428,
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
            'startLine' => 926,
            'endLine' => 926,
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
 * Set the plain text view for the message.
 *
 * @param  string  $textView
 * @param  array  $data
 * @return $this
 */',
        'startLine' => 926,
        'endLine' => 932,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'with' => 
      array (
        'name' => 'with',
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
            'startLine' => 941,
            'endLine' => 941,
            'startColumn' => 26,
            'endColumn' => 29,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 941,
                'endLine' => 941,
                'startTokenPos' => 3798,
                'startFilePos' => 23756,
                'endTokenPos' => 3798,
                'endFilePos' => 23759,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 941,
            'endLine' => 941,
            'startColumn' => 32,
            'endColumn' => 44,
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
 * Set the view data for the message.
 *
 * @param  string|array  $key
 * @param  mixed  $value
 * @return $this
 */',
        'startLine' => 941,
        'endLine' => 950,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
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
            'startLine' => 959,
            'endLine' => 959,
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
                'startLine' => 959,
                'endLine' => 959,
                'startTokenPos' => 3875,
                'startFilePos' => 24223,
                'endTokenPos' => 3876,
                'endFilePos' => 24224,
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
            'startLine' => 959,
            'endLine' => 959,
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
        'startLine' => 959,
        'endLine' => 975,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
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
            'startLine' => 983,
            'endLine' => 983,
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
 * @param  array  $files
 * @return $this
 */',
        'startLine' => 983,
        'endLine' => 994,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'hasAttachment' => 
      array (
        'name' => 'hasAttachment',
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
            'startLine' => 1003,
            'endLine' => 1003,
            'startColumn' => 35,
            'endColumn' => 39,
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
                'startLine' => 1003,
                'endLine' => 1003,
                'startTokenPos' => 4074,
                'startFilePos' => 25328,
                'endTokenPos' => 4075,
                'endFilePos' => 25329,
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
            'startLine' => 1003,
            'endLine' => 1003,
            'startColumn' => 42,
            'endColumn' => 60,
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
 * Determine if the mailable has the given attachment.
 *
 * @param  string|\\Illuminate\\Contracts\\Mail\\Attachable|\\Illuminate\\Mail\\Attachment  $file
 * @param  array  $options
 * @return bool
 */',
        'startLine' => 1003,
        'endLine' => 1034,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'hasEnvelopeAttachment' => 
      array (
        'name' => 'hasEnvelopeAttachment',
        'parameters' => 
        array (
          'attachment' => 
          array (
            'name' => 'attachment',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1043,
            'endLine' => 1043,
            'startColumn' => 44,
            'endColumn' => 54,
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
                'startLine' => 1043,
                'endLine' => 1043,
                'startTokenPos' => 4378,
                'startFilePos' => 26651,
                'endTokenPos' => 4379,
                'endFilePos' => 26652,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1043,
            'endLine' => 1043,
            'startColumn' => 57,
            'endColumn' => 69,
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
 * Determine if the mailable has the given envelope attachment.
 *
 * @param  \\Illuminate\\Mail\\Attachment  $attachment
 * @param  array  $options
 * @return bool
 */',
        'startLine' => 1043,
        'endLine' => 1054,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'attachFromStorage' => 
      array (
        'name' => 'attachFromStorage',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1064,
            'endLine' => 1064,
            'startColumn' => 39,
            'endColumn' => 43,
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
                'startLine' => 1064,
                'endLine' => 1064,
                'startTokenPos' => 4512,
                'startFilePos' => 27334,
                'endTokenPos' => 4512,
                'endFilePos' => 27337,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1064,
            'endLine' => 1064,
            'startColumn' => 46,
            'endColumn' => 57,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'options' => 
          array (
            'name' => 'options',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 1064,
                'endLine' => 1064,
                'startTokenPos' => 4521,
                'startFilePos' => 27357,
                'endTokenPos' => 4522,
                'endFilePos' => 27358,
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
            'startLine' => 1064,
            'endLine' => 1064,
            'startColumn' => 60,
            'endColumn' => 78,
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
 * Attach a file to the message from storage.
 *
 * @param  string  $path
 * @param  string|null  $name
 * @param  array  $options
 * @return $this
 */',
        'startLine' => 1064,
        'endLine' => 1067,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'attachFromStorageDisk' => 
      array (
        'name' => 'attachFromStorageDisk',
        'parameters' => 
        array (
          'disk' => 
          array (
            'name' => 'disk',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1078,
            'endLine' => 1078,
            'startColumn' => 43,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'path' => 
          array (
            'name' => 'path',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1078,
            'endLine' => 1078,
            'startColumn' => 50,
            'endColumn' => 54,
            'parameterIndex' => 1,
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
                'startLine' => 1078,
                'endLine' => 1078,
                'startTokenPos' => 4566,
                'startFilePos' => 27730,
                'endTokenPos' => 4566,
                'endFilePos' => 27733,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1078,
            'endLine' => 1078,
            'startColumn' => 57,
            'endColumn' => 68,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'options' => 
          array (
            'name' => 'options',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 1078,
                'endLine' => 1078,
                'startTokenPos' => 4575,
                'startFilePos' => 27753,
                'endTokenPos' => 4576,
                'endFilePos' => 27754,
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
            'startLine' => 1078,
            'endLine' => 1078,
            'startColumn' => 71,
            'endColumn' => 89,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Attach a file to the message from storage.
 *
 * @param  string  $disk
 * @param  string  $path
 * @param  string|null  $name
 * @param  array  $options
 * @return $this
 */',
        'startLine' => 1078,
        'endLine' => 1090,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'hasAttachmentFromStorage' => 
      array (
        'name' => 'hasAttachmentFromStorage',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1100,
            'endLine' => 1100,
            'startColumn' => 46,
            'endColumn' => 50,
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
                'startLine' => 1100,
                'endLine' => 1100,
                'startTokenPos' => 4695,
                'startFilePos' => 28395,
                'endTokenPos' => 4695,
                'endFilePos' => 28398,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1100,
            'endLine' => 1100,
            'startColumn' => 53,
            'endColumn' => 64,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'options' => 
          array (
            'name' => 'options',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 1100,
                'endLine' => 1100,
                'startTokenPos' => 4704,
                'startFilePos' => 28418,
                'endTokenPos' => 4705,
                'endFilePos' => 28419,
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
            'startLine' => 1100,
            'endLine' => 1100,
            'startColumn' => 67,
            'endColumn' => 85,
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
 * Determine if the mailable has the given attachment from storage.
 *
 * @param  string  $path
 * @param  string|null  $name
 * @param  array  $options
 * @return bool
 */',
        'startLine' => 1100,
        'endLine' => 1103,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'hasAttachmentFromStorageDisk' => 
      array (
        'name' => 'hasAttachmentFromStorageDisk',
        'parameters' => 
        array (
          'disk' => 
          array (
            'name' => 'disk',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1114,
            'endLine' => 1114,
            'startColumn' => 50,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'path' => 
          array (
            'name' => 'path',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1114,
            'endLine' => 1114,
            'startColumn' => 57,
            'endColumn' => 61,
            'parameterIndex' => 1,
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
                'startLine' => 1114,
                'endLine' => 1114,
                'startTokenPos' => 4749,
                'startFilePos' => 28842,
                'endTokenPos' => 4749,
                'endFilePos' => 28845,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1114,
            'endLine' => 1114,
            'startColumn' => 64,
            'endColumn' => 75,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'options' => 
          array (
            'name' => 'options',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 1114,
                'endLine' => 1114,
                'startTokenPos' => 4758,
                'startFilePos' => 28865,
                'endTokenPos' => 4759,
                'endFilePos' => 28866,
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
            'startLine' => 1114,
            'endLine' => 1114,
            'startColumn' => 78,
            'endColumn' => 96,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the mailable has the given attachment from a specific storage disk.
 *
 * @param  string  $disk
 * @param  string  $path
 * @param  string|null  $name
 * @param  array  $options
 * @return bool
 */',
        'startLine' => 1114,
        'endLine' => 1122,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
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
            'startLine' => 1132,
            'endLine' => 1132,
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
            'startLine' => 1132,
            'endLine' => 1132,
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
                'startLine' => 1132,
                'endLine' => 1132,
                'startTokenPos' => 4864,
                'startFilePos' => 29438,
                'endTokenPos' => 4865,
                'endFilePos' => 29439,
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
            'startLine' => 1132,
            'endLine' => 1132,
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
        'startLine' => 1132,
        'endLine' => 1140,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'hasAttachedData' => 
      array (
        'name' => 'hasAttachedData',
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
            'startLine' => 1150,
            'endLine' => 1150,
            'startColumn' => 37,
            'endColumn' => 41,
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
            'startLine' => 1150,
            'endLine' => 1150,
            'startColumn' => 44,
            'endColumn' => 48,
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
                'startLine' => 1150,
                'endLine' => 1150,
                'startTokenPos' => 4957,
                'startFilePos' => 29959,
                'endTokenPos' => 4958,
                'endFilePos' => 29960,
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
            'startLine' => 1150,
            'endLine' => 1150,
            'startColumn' => 51,
            'endColumn' => 69,
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
 * Determine if the mailable has the given data as an attachment.
 *
 * @param  string  $data
 * @param  string  $name
 * @param  array  $options
 * @return bool
 */',
        'startLine' => 1150,
        'endLine' => 1157,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
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
            'startLine' => 1165,
            'endLine' => 1165,
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
        'startLine' => 1165,
        'endLine' => 1170,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'hasTag' => 
      array (
        'name' => 'hasTag',
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
            'startLine' => 1178,
            'endLine' => 1178,
            'startColumn' => 28,
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
 * Determine if the mailable has the given tag.
 *
 * @param  string  $value
 * @return bool
 */',
        'startLine' => 1178,
        'endLine' => 1182,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
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
            'startLine' => 1191,
            'endLine' => 1191,
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
            'startLine' => 1191,
            'endLine' => 1191,
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
        'startLine' => 1191,
        'endLine' => 1196,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'hasMetadata' => 
      array (
        'name' => 'hasMetadata',
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
            'startLine' => 1205,
            'endLine' => 1205,
            'startColumn' => 33,
            'endColumn' => 36,
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
            'startLine' => 1205,
            'endLine' => 1205,
            'startColumn' => 39,
            'endColumn' => 44,
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
 * Determine if the mailable has the given metadata.
 *
 * @param  string  $key
 * @param  string  $value
 * @return bool
 */',
        'startLine' => 1205,
        'endLine' => 1209,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'assertFrom' => 
      array (
        'name' => 'assertFrom',
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
            'startLine' => 1218,
            'endLine' => 1218,
            'startColumn' => 32,
            'endColumn' => 39,
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
                'startLine' => 1218,
                'endLine' => 1218,
                'startTokenPos' => 5240,
                'startFilePos' => 31762,
                'endTokenPos' => 5240,
                'endFilePos' => 31765,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1218,
            'endLine' => 1218,
            'startColumn' => 42,
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
 * Assert that the mailable is from the given address.
 *
 * @param  object|array|string  $address
 * @param  string|null  $name
 * @return $this
 */',
        'startLine' => 1218,
        'endLine' => 1230,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'assertTo' => 
      array (
        'name' => 'assertTo',
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
            'startLine' => 1239,
            'endLine' => 1239,
            'startColumn' => 30,
            'endColumn' => 37,
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
                'startLine' => 1239,
                'endLine' => 1239,
                'startTokenPos' => 5316,
                'startFilePos' => 32293,
                'endTokenPos' => 5316,
                'endFilePos' => 32296,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1239,
            'endLine' => 1239,
            'startColumn' => 40,
            'endColumn' => 51,
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
 * Assert that the mailable has the given recipient.
 *
 * @param  object|array|string  $address
 * @param  string|null  $name
 * @return $this
 */',
        'startLine' => 1239,
        'endLine' => 1251,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'assertHasTo' => 
      array (
        'name' => 'assertHasTo',
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
            'startLine' => 1260,
            'endLine' => 1260,
            'startColumn' => 33,
            'endColumn' => 40,
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
                'startLine' => 1260,
                'endLine' => 1260,
                'startTokenPos' => 5392,
                'startFilePos' => 32845,
                'endTokenPos' => 5392,
                'endFilePos' => 32848,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1260,
            'endLine' => 1260,
            'startColumn' => 43,
            'endColumn' => 54,
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
 * Assert that the mailable has the given recipient.
 *
 * @param  object|array|string  $address
 * @param  string|null  $name
 * @return $this
 */',
        'startLine' => 1260,
        'endLine' => 1263,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'assertHasCc' => 
      array (
        'name' => 'assertHasCc',
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
            'startLine' => 1272,
            'endLine' => 1272,
            'startColumn' => 33,
            'endColumn' => 40,
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
                'startLine' => 1272,
                'endLine' => 1272,
                'startTokenPos' => 5427,
                'startFilePos' => 33143,
                'endTokenPos' => 5427,
                'endFilePos' => 33146,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1272,
            'endLine' => 1272,
            'startColumn' => 43,
            'endColumn' => 54,
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
 * Assert that the mailable has the given recipient.
 *
 * @param  object|array|string  $address
 * @param  string|null  $name
 * @return $this
 */',
        'startLine' => 1272,
        'endLine' => 1284,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'assertHasBcc' => 
      array (
        'name' => 'assertHasBcc',
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
            'startLine' => 1293,
            'endLine' => 1293,
            'startColumn' => 34,
            'endColumn' => 41,
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
                'startLine' => 1293,
                'endLine' => 1293,
                'startTokenPos' => 5503,
                'startFilePos' => 33696,
                'endTokenPos' => 5503,
                'endFilePos' => 33699,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1293,
            'endLine' => 1293,
            'startColumn' => 44,
            'endColumn' => 55,
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
 * Assert that the mailable has the given recipient.
 *
 * @param  object|array|string  $address
 * @param  string|null  $name
 * @return $this
 */',
        'startLine' => 1293,
        'endLine' => 1305,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'assertHasReplyTo' => 
      array (
        'name' => 'assertHasReplyTo',
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
            'startLine' => 1314,
            'endLine' => 1314,
            'startColumn' => 38,
            'endColumn' => 45,
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
                'startLine' => 1314,
                'endLine' => 1314,
                'startTokenPos' => 5579,
                'startFilePos' => 34264,
                'endTokenPos' => 5579,
                'endFilePos' => 34267,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1314,
            'endLine' => 1314,
            'startColumn' => 48,
            'endColumn' => 59,
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
 * Assert that the mailable has the given "reply to" address.
 *
 * @param  object|array|string  $address
 * @param  string|null  $name
 * @return $this
 */',
        'startLine' => 1314,
        'endLine' => 1326,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'formatAssertionRecipient' => 
      array (
        'name' => 'formatAssertionRecipient',
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
            'startLine' => 1335,
            'endLine' => 1335,
            'startColumn' => 47,
            'endColumn' => 54,
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
                'startLine' => 1335,
                'endLine' => 1335,
                'startTokenPos' => 5655,
                'startFilePos' => 34852,
                'endTokenPos' => 5655,
                'endFilePos' => 34855,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1335,
            'endLine' => 1335,
            'startColumn' => 57,
            'endColumn' => 68,
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
 * Format the mailable recipient for display in an assertion message.
 *
 * @param  object|array|string  $address
 * @param  string|null  $name
 * @return string
 */',
        'startLine' => 1335,
        'endLine' => 1346,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'assertHasSubject' => 
      array (
        'name' => 'assertHasSubject',
        'parameters' => 
        array (
          'subject' => 
          array (
            'name' => 'subject',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1354,
            'endLine' => 1354,
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
 * Assert that the mailable has the given subject.
 *
 * @param  string  $subject
 * @return $this
 */',
        'startLine' => 1354,
        'endLine' => 1364,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'assertSeeInHtml' => 
      array (
        'name' => 'assertSeeInHtml',
        'parameters' => 
        array (
          'string' => 
          array (
            'name' => 'string',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1373,
            'endLine' => 1373,
            'startColumn' => 37,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'escape' => 
          array (
            'name' => 'escape',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 1373,
                'endLine' => 1373,
                'startTokenPos' => 5782,
                'startFilePos' => 35703,
                'endTokenPos' => 5782,
                'endFilePos' => 35706,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1373,
            'endLine' => 1373,
            'startColumn' => 46,
            'endColumn' => 59,
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
 * Assert that the given text is present in the HTML email body.
 *
 * @param  string  $string
 * @param  bool  $escape
 * @return $this
 */',
        'startLine' => 1373,
        'endLine' => 1386,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'assertDontSeeInHtml' => 
      array (
        'name' => 'assertDontSeeInHtml',
        'parameters' => 
        array (
          'string' => 
          array (
            'name' => 'string',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1395,
            'endLine' => 1395,
            'startColumn' => 41,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'escape' => 
          array (
            'name' => 'escape',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 1395,
                'endLine' => 1395,
                'startTokenPos' => 5878,
                'startFilePos' => 36315,
                'endTokenPos' => 5878,
                'endFilePos' => 36318,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1395,
            'endLine' => 1395,
            'startColumn' => 50,
            'endColumn' => 63,
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
 * Assert that the given text is not present in the HTML email body.
 *
 * @param  string  $string
 * @param  bool  $escape
 * @return $this
 */',
        'startLine' => 1395,
        'endLine' => 1408,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'assertSeeInOrderInHtml' => 
      array (
        'name' => 'assertSeeInOrderInHtml',
        'parameters' => 
        array (
          'strings' => 
          array (
            'name' => 'strings',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1417,
            'endLine' => 1417,
            'startColumn' => 44,
            'endColumn' => 51,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'escape' => 
          array (
            'name' => 'escape',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 1417,
                'endLine' => 1417,
                'startTokenPos' => 5974,
                'startFilePos' => 36942,
                'endTokenPos' => 5974,
                'endFilePos' => 36945,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1417,
            'endLine' => 1417,
            'startColumn' => 54,
            'endColumn' => 67,
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
 * Assert that the given text strings are present in order in the HTML email body.
 *
 * @param  array  $strings
 * @param  bool  $escape
 * @return $this
 */',
        'startLine' => 1417,
        'endLine' => 1428,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'assertSeeInText' => 
      array (
        'name' => 'assertSeeInText',
        'parameters' => 
        array (
          'string' => 
          array (
            'name' => 'string',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1436,
            'endLine' => 1436,
            'startColumn' => 37,
            'endColumn' => 43,
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
 * Assert that the given text is present in the plain-text email body.
 *
 * @param  string  $string
 * @return $this
 */',
        'startLine' => 1436,
        'endLine' => 1447,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'assertDontSeeInText' => 
      array (
        'name' => 'assertDontSeeInText',
        'parameters' => 
        array (
          'string' => 
          array (
            'name' => 'string',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1455,
            'endLine' => 1455,
            'startColumn' => 41,
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
 * Assert that the given text is not present in the plain-text email body.
 *
 * @param  string  $string
 * @return $this
 */',
        'startLine' => 1455,
        'endLine' => 1466,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'assertSeeInOrderInText' => 
      array (
        'name' => 'assertSeeInOrderInText',
        'parameters' => 
        array (
          'strings' => 
          array (
            'name' => 'strings',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1474,
            'endLine' => 1474,
            'startColumn' => 44,
            'endColumn' => 51,
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
 * Assert that the given text strings are present in order in the plain-text email body.
 *
 * @param  array  $strings
 * @return $this
 */',
        'startLine' => 1474,
        'endLine' => 1481,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'assertHasAttachment' => 
      array (
        'name' => 'assertHasAttachment',
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
            'startLine' => 1490,
            'endLine' => 1490,
            'startColumn' => 41,
            'endColumn' => 45,
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
                'startLine' => 1490,
                'endLine' => 1490,
                'startTokenPos' => 6253,
                'startFilePos' => 38872,
                'endTokenPos' => 6254,
                'endFilePos' => 38873,
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
            'startLine' => 1490,
            'endLine' => 1490,
            'startColumn' => 48,
            'endColumn' => 66,
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
 * Assert the mailable has the given attachment.
 *
 * @param  string|\\Illuminate\\Contracts\\Mail\\Attachable|\\Illuminate\\Mail\\Attachment  $file
 * @param  array  $options
 * @return $this
 */',
        'startLine' => 1490,
        'endLine' => 1500,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'assertHasAttachedData' => 
      array (
        'name' => 'assertHasAttachedData',
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
            'startLine' => 1510,
            'endLine' => 1510,
            'startColumn' => 43,
            'endColumn' => 47,
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
            'startLine' => 1510,
            'endLine' => 1510,
            'startColumn' => 50,
            'endColumn' => 54,
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
                'startLine' => 1510,
                'endLine' => 1510,
                'startTokenPos' => 6314,
                'startFilePos' => 39364,
                'endTokenPos' => 6315,
                'endFilePos' => 39365,
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
            'startLine' => 1510,
            'endLine' => 1510,
            'startColumn' => 57,
            'endColumn' => 75,
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
 * Assert the mailable has the given data as an attachment.
 *
 * @param  string  $data
 * @param  string  $name
 * @param  array  $options
 * @return $this
 */',
        'startLine' => 1510,
        'endLine' => 1520,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'assertHasAttachmentFromStorage' => 
      array (
        'name' => 'assertHasAttachmentFromStorage',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1530,
            'endLine' => 1530,
            'startColumn' => 52,
            'endColumn' => 56,
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
                'startLine' => 1530,
                'endLine' => 1530,
                'startTokenPos' => 6373,
                'startFilePos' => 39865,
                'endTokenPos' => 6373,
                'endFilePos' => 39868,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1530,
            'endLine' => 1530,
            'startColumn' => 59,
            'endColumn' => 70,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'options' => 
          array (
            'name' => 'options',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 1530,
                'endLine' => 1530,
                'startTokenPos' => 6382,
                'startFilePos' => 39888,
                'endTokenPos' => 6383,
                'endFilePos' => 39889,
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
            'startLine' => 1530,
            'endLine' => 1530,
            'startColumn' => 73,
            'endColumn' => 91,
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
 * Assert the mailable has the given attachment from storage.
 *
 * @param  string  $path
 * @param  string|null  $name
 * @param  array  $options
 * @return $this
 */',
        'startLine' => 1530,
        'endLine' => 1540,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'assertHasAttachmentFromStorageDisk' => 
      array (
        'name' => 'assertHasAttachmentFromStorageDisk',
        'parameters' => 
        array (
          'disk' => 
          array (
            'name' => 'disk',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1551,
            'endLine' => 1551,
            'startColumn' => 56,
            'endColumn' => 60,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'path' => 
          array (
            'name' => 'path',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1551,
            'endLine' => 1551,
            'startColumn' => 63,
            'endColumn' => 67,
            'parameterIndex' => 1,
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
                'startLine' => 1551,
                'endLine' => 1551,
                'startTokenPos' => 6444,
                'startFilePos' => 40454,
                'endTokenPos' => 6444,
                'endFilePos' => 40457,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1551,
            'endLine' => 1551,
            'startColumn' => 70,
            'endColumn' => 81,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'options' => 
          array (
            'name' => 'options',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 1551,
                'endLine' => 1551,
                'startTokenPos' => 6453,
                'startFilePos' => 40477,
                'endTokenPos' => 6454,
                'endFilePos' => 40478,
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
            'startLine' => 1551,
            'endLine' => 1551,
            'startColumn' => 84,
            'endColumn' => 102,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert the mailable has the given attachment from a specific storage disk.
 *
 * @param  string  $disk
 * @param  string  $path
 * @param  string|null  $name
 * @param  array  $options
 * @return $this
 */',
        'startLine' => 1551,
        'endLine' => 1561,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'assertHasTag' => 
      array (
        'name' => 'assertHasTag',
        'parameters' => 
        array (
          'tag' => 
          array (
            'name' => 'tag',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1569,
            'endLine' => 1569,
            'startColumn' => 34,
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
 * Assert that the mailable has the given tag.
 *
 * @param  string  $tag
 * @return $this
 */',
        'startLine' => 1569,
        'endLine' => 1579,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'assertHasMetadata' => 
      array (
        'name' => 'assertHasMetadata',
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
            'startLine' => 1588,
            'endLine' => 1588,
            'startColumn' => 39,
            'endColumn' => 42,
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
            'startLine' => 1588,
            'endLine' => 1588,
            'startColumn' => 45,
            'endColumn' => 50,
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
 * Assert that the mailable has the given metadata.
 *
 * @param  string  $key
 * @param  string  $value
 * @return $this
 */',
        'startLine' => 1588,
        'endLine' => 1598,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'renderForAssertions' => 
      array (
        'name' => 'renderForAssertions',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Render the HTML and plain-text version of the mailable into views for assertions.
 *
 * @return array
 *
 * @throws \\ReflectionException
 */',
        'startLine' => 1607,
        'endLine' => 1634,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'prepareMailableForDelivery' => 
      array (
        'name' => 'prepareMailableForDelivery',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Prepare the mailable instance for delivery.
 *
 * @return void
 */',
        'startLine' => 1641,
        'endLine' => 1651,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'ensureHeadersAreHydrated' => 
      array (
        'name' => 'ensureHeadersAreHydrated',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Ensure the mailable\'s headers are hydrated from the "headers" method.
 *
 * @return void
 */',
        'startLine' => 1658,
        'endLine' => 1679,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'ensureEnvelopeIsHydrated' => 
      array (
        'name' => 'ensureEnvelopeIsHydrated',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Ensure the mailable\'s "envelope" data is hydrated from the "envelope" method.
 *
 * @return void
 */',
        'startLine' => 1686,
        'endLine' => 1719,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'ensureContentIsHydrated' => 
      array (
        'name' => 'ensureContentIsHydrated',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Ensure the mailable\'s content is hydrated from the "content" method.
 *
 * @return void
 */',
        'startLine' => 1726,
        'endLine' => 1757,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'ensureAttachmentsAreHydrated' => 
      array (
        'name' => 'ensureAttachmentsAreHydrated',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Ensure the mailable\'s attachments are hydrated from the "attachments" method.
 *
 * @return void
 */',
        'startLine' => 1764,
        'endLine' => 1776,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'mailer' => 
      array (
        'name' => 'mailer',
        'parameters' => 
        array (
          'mailer' => 
          array (
            'name' => 'mailer',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1784,
            'endLine' => 1784,
            'startColumn' => 28,
            'endColumn' => 34,
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
 * Set the name of the mailer that should send the message.
 *
 * @param  string  $mailer
 * @return $this
 */',
        'startLine' => 1784,
        'endLine' => 1789,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
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
            'startLine' => 1797,
            'endLine' => 1797,
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
        'startLine' => 1797,
        'endLine' => 1802,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      'buildViewDataUsing' => 
      array (
        'name' => 'buildViewDataUsing',
        'parameters' => 
        array (
          'callback' => 
          array (
            'name' => 'callback',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'callable',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1810,
            'endLine' => 1810,
            'startColumn' => 47,
            'endColumn' => 64,
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
 * Register a callback to be called while building the view data.
 *
 * @param  callable  $callback
 * @return void
 */',
        'startLine' => 1810,
        'endLine' => 1813,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
      '__call' => 
      array (
        'name' => '__call',
        'parameters' => 
        array (
          'method' => 
          array (
            'name' => 'method',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1824,
            'endLine' => 1824,
            'startColumn' => 28,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'parameters' => 
          array (
            'name' => 'parameters',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1824,
            'endLine' => 1824,
            'startColumn' => 37,
            'endColumn' => 47,
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
 * Dynamically bind parameters to the message.
 *
 * @param  string  $method
 * @param  array  $parameters
 * @return $this
 *
 * @throws \\BadMethodCallException
 */',
        'startLine' => 1824,
        'endLine' => 1835,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Mail',
        'declaringClassName' => 'Illuminate\\Mail\\Mailable',
        'implementingClassName' => 'Illuminate\\Mail\\Mailable',
        'currentClassName' => 'Illuminate\\Mail\\Mailable',
        'aliasName' => NULL,
      ),
    ),
    'traitsData' => 
    array (
      'aliases' => 
      array (
        'Illuminate\\Support\\Traits\\Conditionable' => 
        array (
          0 => 
          array (
            'alias' => 'macroCall',
            'method' => '__call',
            'hash' => 'illuminate\\support\\traits\\conditionable::__call',
          ),
        ),
        'Illuminate\\Support\\Traits\\ForwardsCalls' => 
        array (
          0 => 
          array (
            'alias' => 'macroCall',
            'method' => '__call',
            'hash' => 'illuminate\\support\\traits\\forwardscalls::__call',
          ),
        ),
        'Illuminate\\Support\\Traits\\Localizable' => 
        array (
          0 => 
          array (
            'alias' => 'macroCall',
            'method' => '__call',
            'hash' => 'illuminate\\support\\traits\\localizable::__call',
          ),
        ),
        'Illuminate\\Support\\Traits\\Tappable' => 
        array (
          0 => 
          array (
            'alias' => 'macroCall',
            'method' => '__call',
            'hash' => 'illuminate\\support\\traits\\tappable::__call',
          ),
        ),
        'Illuminate\\Support\\Traits\\Macroable' => 
        array (
          0 => 
          array (
            'alias' => 'macroCall',
            'method' => '__call',
            'hash' => 'illuminate\\support\\traits\\macroable::__call',
          ),
        ),
      ),
      'modifiers' => 
      array (
      ),
      'precedences' => 
      array (
      ),
      'hashes' => 
      array (
        'illuminate\\support\\traits\\conditionable::__call' => 'Illuminate\\Support\\Traits\\Conditionable::__call',
        'illuminate\\support\\traits\\forwardscalls::__call' => 'Illuminate\\Support\\Traits\\ForwardsCalls::__call',
        'illuminate\\support\\traits\\localizable::__call' => 'Illuminate\\Support\\Traits\\Localizable::__call',
        'illuminate\\support\\traits\\tappable::__call' => 'Illuminate\\Support\\Traits\\Tappable::__call',
        'illuminate\\support\\traits\\macroable::__call' => 'Illuminate\\Support\\Traits\\Macroable::__call',
      ),
    ),
  ),
));