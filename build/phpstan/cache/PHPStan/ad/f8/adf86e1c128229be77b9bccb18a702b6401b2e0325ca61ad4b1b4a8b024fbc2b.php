<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../symfony/mailer/Envelope.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Symfony\Component\Mailer\Envelope
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6afd3af92b19a02e90a4a424ac084ff6e9a6ddfa758416f91051ae330b8f6fe3-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Symfony\\Component\\Mailer\\Envelope',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../symfony/mailer/Envelope.php',
      ),
    ),
    'namespace' => 'Symfony\\Component\\Mailer',
    'name' => 'Symfony\\Component\\Mailer\\Envelope',
    'shortName' => 'Envelope',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @author Fabien Potencier <fabien@symfony.com>
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 22,
    'endLine' => 117,
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
      'sender' => 
      array (
        'declaringClassName' => 'Symfony\\Component\\Mailer\\Envelope',
        'implementingClassName' => 'Symfony\\Component\\Mailer\\Envelope',
        'name' => 'sender',
        'modifiers' => 4,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Symfony\\Component\\Mime\\Address',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 24,
        'endLine' => 24,
        'startColumn' => 5,
        'endColumn' => 28,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'recipients' => 
      array (
        'declaringClassName' => 'Symfony\\Component\\Mailer\\Envelope',
        'implementingClassName' => 'Symfony\\Component\\Mailer\\Envelope',
        'name' => 'recipients',
        'modifiers' => 4,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 25,
            'endLine' => 25,
            'startTokenPos' => 52,
            'startFilePos' => 606,
            'endTokenPos' => 53,
            'endFilePos' => 607,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 25,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 35,
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
          'sender' => 
          array (
            'name' => 'sender',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Symfony\\Component\\Mime\\Address',
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
            'startColumn' => 33,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'recipients' => 
          array (
            'name' => 'recipients',
            'default' => NULL,
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
            'startLine' => 30,
            'endLine' => 30,
            'startColumn' => 50,
            'endColumn' => 66,
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
 * @param Address[] $recipients
 */',
        'startLine' => 30,
        'endLine' => 34,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\Mailer',
        'declaringClassName' => 'Symfony\\Component\\Mailer\\Envelope',
        'implementingClassName' => 'Symfony\\Component\\Mailer\\Envelope',
        'currentClassName' => 'Symfony\\Component\\Mailer\\Envelope',
        'aliasName' => NULL,
      ),
      'create' => 
      array (
        'name' => 'create',
        'parameters' => 
        array (
          'message' => 
          array (
            'name' => 'message',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Symfony\\Component\\Mime\\RawMessage',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 36,
            'endLine' => 36,
            'startColumn' => 35,
            'endColumn' => 53,
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
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 36,
        'endLine' => 43,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Symfony\\Component\\Mailer',
        'declaringClassName' => 'Symfony\\Component\\Mailer\\Envelope',
        'implementingClassName' => 'Symfony\\Component\\Mailer\\Envelope',
        'currentClassName' => 'Symfony\\Component\\Mailer\\Envelope',
        'aliasName' => NULL,
      ),
      'setSender' => 
      array (
        'name' => 'setSender',
        'parameters' => 
        array (
          'sender' => 
          array (
            'name' => 'sender',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Symfony\\Component\\Mime\\Address',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 45,
            'endLine' => 45,
            'startColumn' => 31,
            'endColumn' => 45,
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
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 45,
        'endLine' => 52,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\Mailer',
        'declaringClassName' => 'Symfony\\Component\\Mailer\\Envelope',
        'implementingClassName' => 'Symfony\\Component\\Mailer\\Envelope',
        'currentClassName' => 'Symfony\\Component\\Mailer\\Envelope',
        'aliasName' => NULL,
      ),
      'getSender' => 
      array (
        'name' => 'getSender',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Symfony\\Component\\Mime\\Address',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return Address Returns a "mailbox" as specified by RFC 2822
 *                 Must be converted to an "addr-spec" when used as a "MAIL FROM" value in SMTP (use getAddress())
 */',
        'startLine' => 58,
        'endLine' => 61,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\Mailer',
        'declaringClassName' => 'Symfony\\Component\\Mailer\\Envelope',
        'implementingClassName' => 'Symfony\\Component\\Mailer\\Envelope',
        'currentClassName' => 'Symfony\\Component\\Mailer\\Envelope',
        'aliasName' => NULL,
      ),
      'setRecipients' => 
      array (
        'name' => 'setRecipients',
        'parameters' => 
        array (
          'recipients' => 
          array (
            'name' => 'recipients',
            'default' => NULL,
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
            'startLine' => 66,
            'endLine' => 66,
            'startColumn' => 35,
            'endColumn' => 51,
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
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param Address[] $recipients
 */',
        'startLine' => 66,
        'endLine' => 79,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\Mailer',
        'declaringClassName' => 'Symfony\\Component\\Mailer\\Envelope',
        'implementingClassName' => 'Symfony\\Component\\Mailer\\Envelope',
        'currentClassName' => 'Symfony\\Component\\Mailer\\Envelope',
        'aliasName' => NULL,
      ),
      'getRecipients' => 
      array (
        'name' => 'getRecipients',
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
 * @return Address[]
 */',
        'startLine' => 84,
        'endLine' => 87,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\Mailer',
        'declaringClassName' => 'Symfony\\Component\\Mailer\\Envelope',
        'implementingClassName' => 'Symfony\\Component\\Mailer\\Envelope',
        'currentClassName' => 'Symfony\\Component\\Mailer\\Envelope',
        'aliasName' => NULL,
      ),
      'anyAddressHasUnicodeLocalpart' => 
      array (
        'name' => 'anyAddressHasUnicodeLocalpart',
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
 * Returns true if any address\' localpart contains at least one
 * non-ASCII character, and false if all addresses have all-ASCII
 * localparts.
 *
 * This helps to decide whether to the SMTPUTF8 extensions (RFC
 * 6530 and following) for any given message.
 *
 * The SMTPUTF8 extension is strictly required if any address
 * contains a non-ASCII character in its localpart. If non-ASCII
 * is only used in domains (e.g. horst@freiherr-von-mühlhausen.de)
 * then it is possible to send the message using IDN encoding
 * instead of SMTPUTF8. The most common software will display the
 * message as intended.
 */',
        'startLine' => 104,
        'endLine' => 116,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\Mailer',
        'declaringClassName' => 'Symfony\\Component\\Mailer\\Envelope',
        'implementingClassName' => 'Symfony\\Component\\Mailer\\Envelope',
        'currentClassName' => 'Symfony\\Component\\Mailer\\Envelope',
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