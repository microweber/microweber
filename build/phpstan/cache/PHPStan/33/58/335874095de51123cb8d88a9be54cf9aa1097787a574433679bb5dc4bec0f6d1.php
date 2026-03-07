<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../symfony/mailer/Transport/Smtp/Auth/CramMd5Authenticator.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Symfony\Component\Mailer\Transport\Smtp\Auth\CramMd5Authenticator
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-26740f9d92a3cb06bd7d2b8ca8a96fbae167162e46a6724c8a5ad1538d9a4b94-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Symfony\\Component\\Mailer\\Transport\\Smtp\\Auth\\CramMd5Authenticator',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../symfony/mailer/Transport/Smtp/Auth/CramMd5Authenticator.php',
      ),
    ),
    'namespace' => 'Symfony\\Component\\Mailer\\Transport\\Smtp\\Auth',
    'name' => 'Symfony\\Component\\Mailer\\Transport\\Smtp\\Auth\\CramMd5Authenticator',
    'shortName' => 'CramMd5Authenticator',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Handles CRAM-MD5 authentication.
 *
 * @author Chris Corbyn
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 22,
    'endLine' => 64,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'Symfony\\Component\\Mailer\\Transport\\Smtp\\Auth\\AuthenticatorInterface',
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'getAuthKeyword' => 
      array (
        'name' => 'getAuthKeyword',
        'parameters' => 
        array (
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
        'docComment' => NULL,
        'startLine' => 24,
        'endLine' => 27,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\Mailer\\Transport\\Smtp\\Auth',
        'declaringClassName' => 'Symfony\\Component\\Mailer\\Transport\\Smtp\\Auth\\CramMd5Authenticator',
        'implementingClassName' => 'Symfony\\Component\\Mailer\\Transport\\Smtp\\Auth\\CramMd5Authenticator',
        'currentClassName' => 'Symfony\\Component\\Mailer\\Transport\\Smtp\\Auth\\CramMd5Authenticator',
        'aliasName' => NULL,
      ),
      'authenticate' => 
      array (
        'name' => 'authenticate',
        'parameters' => 
        array (
          'client' => 
          array (
            'name' => 'client',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Symfony\\Component\\Mailer\\Transport\\Smtp\\EsmtpTransport',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 32,
            'endLine' => 32,
            'startColumn' => 34,
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
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @see https://www.ietf.org/rfc/rfc4954.txt
 */',
        'startLine' => 32,
        'endLine' => 38,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\Mailer\\Transport\\Smtp\\Auth',
        'declaringClassName' => 'Symfony\\Component\\Mailer\\Transport\\Smtp\\Auth\\CramMd5Authenticator',
        'implementingClassName' => 'Symfony\\Component\\Mailer\\Transport\\Smtp\\Auth\\CramMd5Authenticator',
        'currentClassName' => 'Symfony\\Component\\Mailer\\Transport\\Smtp\\Auth\\CramMd5Authenticator',
        'aliasName' => NULL,
      ),
      'getResponse' => 
      array (
        'name' => 'getResponse',
        'parameters' => 
        array (
          'secret' => 
          array (
            'name' => 'secret',
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
              0 => 
              array (
                'name' => 'SensitiveParameter',
                'isRepeated' => false,
                'arguments' => 
                array (
                ),
              ),
            ),
            'startLine' => 43,
            'endLine' => 43,
            'startColumn' => 34,
            'endColumn' => 70,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'challenge' => 
          array (
            'name' => 'challenge',
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
            'startLine' => 43,
            'endLine' => 43,
            'startColumn' => 73,
            'endColumn' => 89,
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
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Generates a CRAM-MD5 response from a server challenge.
 */',
        'startLine' => 43,
        'endLine' => 63,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Symfony\\Component\\Mailer\\Transport\\Smtp\\Auth',
        'declaringClassName' => 'Symfony\\Component\\Mailer\\Transport\\Smtp\\Auth\\CramMd5Authenticator',
        'implementingClassName' => 'Symfony\\Component\\Mailer\\Transport\\Smtp\\Auth\\CramMd5Authenticator',
        'currentClassName' => 'Symfony\\Component\\Mailer\\Transport\\Smtp\\Auth\\CramMd5Authenticator',
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