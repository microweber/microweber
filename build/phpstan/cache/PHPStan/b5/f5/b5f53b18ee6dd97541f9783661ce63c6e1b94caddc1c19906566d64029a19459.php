<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Notifications/Channels/MailChannel.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Notifications\Channels\MailChannel
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-f07bbe09017d4dc8e8ba593627ed63d3b4b38d0c00f4e0844d9fe151ca9ac654-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Notifications\\Channels\\MailChannel',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/framework/src/Illuminate/Notifications/Channels/MailChannel.php',
      ),
    ),
    'namespace' => 'Illuminate\\Notifications\\Channels',
    'name' => 'Illuminate\\Notifications\\Channels\\MailChannel',
    'shortName' => 'MailChannel',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 18,
    'endLine' => 306,
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
      'mailer' => 
      array (
        'declaringClassName' => 'Illuminate\\Notifications\\Channels\\MailChannel',
        'implementingClassName' => 'Illuminate\\Notifications\\Channels\\MailChannel',
        'name' => 'mailer',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The mailer implementation.
 *
 * @var \\Illuminate\\Contracts\\Mail\\Factory
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 25,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 22,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'markdown' => 
      array (
        'declaringClassName' => 'Illuminate\\Notifications\\Channels\\MailChannel',
        'implementingClassName' => 'Illuminate\\Notifications\\Channels\\MailChannel',
        'name' => 'markdown',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The markdown implementation.
 *
 * @var \\Illuminate\\Mail\\Markdown
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 32,
        'endLine' => 32,
        'startColumn' => 5,
        'endColumn' => 24,
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
          'mailer' => 
          array (
            'name' => 'mailer',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Contracts\\Mail\\Factory',
                'isIdentifier' => false,
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
            'startColumn' => 33,
            'endColumn' => 51,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'markdown' => 
          array (
            'name' => 'markdown',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Mail\\Markdown',
                'isIdentifier' => false,
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
            'startColumn' => 54,
            'endColumn' => 71,
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
 * Create a new mail channel instance.
 *
 * @param  \\Illuminate\\Contracts\\Mail\\Factory  $mailer
 * @param  \\Illuminate\\Mail\\Markdown  $markdown
 * @return void
 */',
        'startLine' => 41,
        'endLine' => 45,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Notifications\\Channels',
        'declaringClassName' => 'Illuminate\\Notifications\\Channels\\MailChannel',
        'implementingClassName' => 'Illuminate\\Notifications\\Channels\\MailChannel',
        'currentClassName' => 'Illuminate\\Notifications\\Channels\\MailChannel',
        'aliasName' => NULL,
      ),
      'send' => 
      array (
        'name' => 'send',
        'parameters' => 
        array (
          'notifiable' => 
          array (
            'name' => 'notifiable',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 54,
            'endLine' => 54,
            'startColumn' => 26,
            'endColumn' => 36,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'notification' => 
          array (
            'name' => 'notification',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Notifications\\Notification',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 54,
            'endLine' => 54,
            'startColumn' => 39,
            'endColumn' => 64,
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
 * Send the given notification.
 *
 * @param  mixed  $notifiable
 * @param  \\Illuminate\\Notifications\\Notification  $notification
 * @return \\Illuminate\\Mail\\SentMessage|null
 */',
        'startLine' => 54,
        'endLine' => 72,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Notifications\\Channels',
        'declaringClassName' => 'Illuminate\\Notifications\\Channels\\MailChannel',
        'implementingClassName' => 'Illuminate\\Notifications\\Channels\\MailChannel',
        'currentClassName' => 'Illuminate\\Notifications\\Channels\\MailChannel',
        'aliasName' => NULL,
      ),
      'messageBuilder' => 
      array (
        'name' => 'messageBuilder',
        'parameters' => 
        array (
          'notifiable' => 
          array (
            'name' => 'notifiable',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 82,
            'endLine' => 82,
            'startColumn' => 39,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'notification' => 
          array (
            'name' => 'notification',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 82,
            'endLine' => 82,
            'startColumn' => 52,
            'endColumn' => 64,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
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
            'startLine' => 82,
            'endLine' => 82,
            'startColumn' => 67,
            'endColumn' => 74,
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
 * Get the mailer Closure for the message.
 *
 * @param  mixed  $notifiable
 * @param  \\Illuminate\\Notifications\\Notification  $notification
 * @param  \\Illuminate\\Notifications\\Messages\\MailMessage  $message
 * @return \\Closure
 */',
        'startLine' => 82,
        'endLine' => 87,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Notifications\\Channels',
        'declaringClassName' => 'Illuminate\\Notifications\\Channels\\MailChannel',
        'implementingClassName' => 'Illuminate\\Notifications\\Channels\\MailChannel',
        'currentClassName' => 'Illuminate\\Notifications\\Channels\\MailChannel',
        'aliasName' => NULL,
      ),
      'buildView' => 
      array (
        'name' => 'buildView',
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
            'startLine' => 95,
            'endLine' => 95,
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
 * Build the notification\'s view.
 *
 * @param  \\Illuminate\\Notifications\\Messages\\MailMessage  $message
 * @return string|array
 */',
        'startLine' => 95,
        'endLine' => 105,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Notifications\\Channels',
        'declaringClassName' => 'Illuminate\\Notifications\\Channels\\MailChannel',
        'implementingClassName' => 'Illuminate\\Notifications\\Channels\\MailChannel',
        'currentClassName' => 'Illuminate\\Notifications\\Channels\\MailChannel',
        'aliasName' => NULL,
      ),
      'buildMarkdownHtml' => 
      array (
        'name' => 'buildMarkdownHtml',
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
            'startLine' => 113,
            'endLine' => 113,
            'startColumn' => 42,
            'endColumn' => 49,
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
 * @param  \\Illuminate\\Notifications\\Messages\\MailMessage  $message
 * @return \\Closure
 */',
        'startLine' => 113,
        'endLine' => 118,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Notifications\\Channels',
        'declaringClassName' => 'Illuminate\\Notifications\\Channels\\MailChannel',
        'implementingClassName' => 'Illuminate\\Notifications\\Channels\\MailChannel',
        'currentClassName' => 'Illuminate\\Notifications\\Channels\\MailChannel',
        'aliasName' => NULL,
      ),
      'buildMarkdownText' => 
      array (
        'name' => 'buildMarkdownText',
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
            'startLine' => 126,
            'endLine' => 126,
            'startColumn' => 42,
            'endColumn' => 49,
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
 * @param  \\Illuminate\\Notifications\\Messages\\MailMessage  $message
 * @return \\Closure
 */',
        'startLine' => 126,
        'endLine' => 131,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Notifications\\Channels',
        'declaringClassName' => 'Illuminate\\Notifications\\Channels\\MailChannel',
        'implementingClassName' => 'Illuminate\\Notifications\\Channels\\MailChannel',
        'currentClassName' => 'Illuminate\\Notifications\\Channels\\MailChannel',
        'aliasName' => NULL,
      ),
      'markdownRenderer' => 
      array (
        'name' => 'markdownRenderer',
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
            'startLine' => 139,
            'endLine' => 139,
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
 * Get the Markdown implementation.
 *
 * @param  \\Illuminate\\Notifications\\Messages\\MailMessage  $message
 * @return \\Illuminate\\Mail\\Markdown
 */',
        'startLine' => 139,
        'endLine' => 146,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Notifications\\Channels',
        'declaringClassName' => 'Illuminate\\Notifications\\Channels\\MailChannel',
        'implementingClassName' => 'Illuminate\\Notifications\\Channels\\MailChannel',
        'currentClassName' => 'Illuminate\\Notifications\\Channels\\MailChannel',
        'aliasName' => NULL,
      ),
      'additionalMessageData' => 
      array (
        'name' => 'additionalMessageData',
        'parameters' => 
        array (
          'notification' => 
          array (
            'name' => 'notification',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 154,
            'endLine' => 154,
            'startColumn' => 46,
            'endColumn' => 58,
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
 * Get additional meta-data to pass along with the view data.
 *
 * @param  \\Illuminate\\Notifications\\Notification  $notification
 * @return array
 */',
        'startLine' => 154,
        'endLine' => 164,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Notifications\\Channels',
        'declaringClassName' => 'Illuminate\\Notifications\\Channels\\MailChannel',
        'implementingClassName' => 'Illuminate\\Notifications\\Channels\\MailChannel',
        'currentClassName' => 'Illuminate\\Notifications\\Channels\\MailChannel',
        'aliasName' => NULL,
      ),
      'buildMessage' => 
      array (
        'name' => 'buildMessage',
        'parameters' => 
        array (
          'mailMessage' => 
          array (
            'name' => 'mailMessage',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 175,
            'endLine' => 175,
            'startColumn' => 37,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'notifiable' => 
          array (
            'name' => 'notifiable',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 175,
            'endLine' => 175,
            'startColumn' => 51,
            'endColumn' => 61,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'notification' => 
          array (
            'name' => 'notification',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 175,
            'endLine' => 175,
            'startColumn' => 64,
            'endColumn' => 76,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
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
            'startLine' => 175,
            'endLine' => 175,
            'startColumn' => 79,
            'endColumn' => 86,
            'parameterIndex' => 3,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Build the mail message.
 *
 * @param  \\Illuminate\\Mail\\Message  $mailMessage
 * @param  mixed  $notifiable
 * @param  \\Illuminate\\Notifications\\Notification  $notification
 * @param  \\Illuminate\\Notifications\\Messages\\MailMessage  $message
 * @return void
 */',
        'startLine' => 175,
        'endLine' => 202,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Notifications\\Channels',
        'declaringClassName' => 'Illuminate\\Notifications\\Channels\\MailChannel',
        'implementingClassName' => 'Illuminate\\Notifications\\Channels\\MailChannel',
        'currentClassName' => 'Illuminate\\Notifications\\Channels\\MailChannel',
        'aliasName' => NULL,
      ),
      'addressMessage' => 
      array (
        'name' => 'addressMessage',
        'parameters' => 
        array (
          'mailMessage' => 
          array (
            'name' => 'mailMessage',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 213,
            'endLine' => 213,
            'startColumn' => 39,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'notifiable' => 
          array (
            'name' => 'notifiable',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 213,
            'endLine' => 213,
            'startColumn' => 53,
            'endColumn' => 63,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'notification' => 
          array (
            'name' => 'notification',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 213,
            'endLine' => 213,
            'startColumn' => 66,
            'endColumn' => 78,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
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
            'startLine' => 213,
            'endLine' => 213,
            'startColumn' => 81,
            'endColumn' => 88,
            'parameterIndex' => 3,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Address the mail message.
 *
 * @param  \\Illuminate\\Mail\\Message  $mailMessage
 * @param  mixed  $notifiable
 * @param  \\Illuminate\\Notifications\\Notification  $notification
 * @param  \\Illuminate\\Notifications\\Messages\\MailMessage  $message
 * @return void
 */',
        'startLine' => 213,
        'endLine' => 230,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Notifications\\Channels',
        'declaringClassName' => 'Illuminate\\Notifications\\Channels\\MailChannel',
        'implementingClassName' => 'Illuminate\\Notifications\\Channels\\MailChannel',
        'currentClassName' => 'Illuminate\\Notifications\\Channels\\MailChannel',
        'aliasName' => NULL,
      ),
      'addSender' => 
      array (
        'name' => 'addSender',
        'parameters' => 
        array (
          'mailMessage' => 
          array (
            'name' => 'mailMessage',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 239,
            'endLine' => 239,
            'startColumn' => 34,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
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
            'startLine' => 239,
            'endLine' => 239,
            'startColumn' => 48,
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
 * Add the "from" and "reply to" addresses to the message.
 *
 * @param  \\Illuminate\\Mail\\Message  $mailMessage
 * @param  \\Illuminate\\Notifications\\Messages\\MailMessage  $message
 * @return void
 */',
        'startLine' => 239,
        'endLine' => 250,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Notifications\\Channels',
        'declaringClassName' => 'Illuminate\\Notifications\\Channels\\MailChannel',
        'implementingClassName' => 'Illuminate\\Notifications\\Channels\\MailChannel',
        'currentClassName' => 'Illuminate\\Notifications\\Channels\\MailChannel',
        'aliasName' => NULL,
      ),
      'getRecipients' => 
      array (
        'name' => 'getRecipients',
        'parameters' => 
        array (
          'notifiable' => 
          array (
            'name' => 'notifiable',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 260,
            'endLine' => 260,
            'startColumn' => 38,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'notification' => 
          array (
            'name' => 'notification',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 260,
            'endLine' => 260,
            'startColumn' => 51,
            'endColumn' => 63,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
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
            'startLine' => 260,
            'endLine' => 260,
            'startColumn' => 66,
            'endColumn' => 73,
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
 * Get the recipients of the given message.
 *
 * @param  mixed  $notifiable
 * @param  \\Illuminate\\Notifications\\Notification  $notification
 * @param  \\Illuminate\\Notifications\\Messages\\MailMessage  $message
 * @return mixed
 */',
        'startLine' => 260,
        'endLine' => 271,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Notifications\\Channels',
        'declaringClassName' => 'Illuminate\\Notifications\\Channels\\MailChannel',
        'implementingClassName' => 'Illuminate\\Notifications\\Channels\\MailChannel',
        'currentClassName' => 'Illuminate\\Notifications\\Channels\\MailChannel',
        'aliasName' => NULL,
      ),
      'addAttachments' => 
      array (
        'name' => 'addAttachments',
        'parameters' => 
        array (
          'mailMessage' => 
          array (
            'name' => 'mailMessage',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 280,
            'endLine' => 280,
            'startColumn' => 39,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
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
            'startLine' => 280,
            'endLine' => 280,
            'startColumn' => 53,
            'endColumn' => 60,
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
 * Add the attachments to the message.
 *
 * @param  \\Illuminate\\Mail\\Message  $mailMessage
 * @param  \\Illuminate\\Notifications\\Messages\\MailMessage  $message
 * @return void
 */',
        'startLine' => 280,
        'endLine' => 289,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Notifications\\Channels',
        'declaringClassName' => 'Illuminate\\Notifications\\Channels\\MailChannel',
        'implementingClassName' => 'Illuminate\\Notifications\\Channels\\MailChannel',
        'currentClassName' => 'Illuminate\\Notifications\\Channels\\MailChannel',
        'aliasName' => NULL,
      ),
      'runCallbacks' => 
      array (
        'name' => 'runCallbacks',
        'parameters' => 
        array (
          'mailMessage' => 
          array (
            'name' => 'mailMessage',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 298,
            'endLine' => 298,
            'startColumn' => 37,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
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
            'startLine' => 298,
            'endLine' => 298,
            'startColumn' => 51,
            'endColumn' => 58,
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
 * Run the callbacks for the message.
 *
 * @param  \\Illuminate\\Mail\\Message  $mailMessage
 * @param  \\Illuminate\\Notifications\\Messages\\MailMessage  $message
 * @return $this
 */',
        'startLine' => 298,
        'endLine' => 305,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Notifications\\Channels',
        'declaringClassName' => 'Illuminate\\Notifications\\Channels\\MailChannel',
        'implementingClassName' => 'Illuminate\\Notifications\\Channels\\MailChannel',
        'currentClassName' => 'Illuminate\\Notifications\\Channels\\MailChannel',
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