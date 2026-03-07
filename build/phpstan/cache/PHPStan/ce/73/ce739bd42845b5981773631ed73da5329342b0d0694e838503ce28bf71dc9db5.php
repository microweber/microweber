<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../symfony/mailer/Transport.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Symfony\Component\Mailer\Transport
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-ab9979029452dd38396cad40fc7f0433d361627e55ac76100175a621637f9d5d-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Symfony\\Component\\Mailer\\Transport',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../symfony/mailer/Transport.php',
      ),
    ),
    'namespace' => 'Symfony\\Component\\Mailer',
    'name' => 'Symfony\\Component\\Mailer\\Transport',
    'shortName' => 'Transport',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 32,
    'docComment' => '/**
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 53,
    'endLine' => 201,
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
      'FACTORY_CLASSES' => 
      array (
        'declaringClassName' => 'Symfony\\Component\\Mailer\\Transport',
        'implementingClassName' => 'Symfony\\Component\\Mailer\\Transport',
        'name' => 'FACTORY_CLASSES',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[\\Symfony\\Component\\Mailer\\Bridge\\AhaSend\\Transport\\AhaSendTransportFactory::class, \\Symfony\\Component\\Mailer\\Bridge\\Azure\\Transport\\AzureTransportFactory::class, \\Symfony\\Component\\Mailer\\Bridge\\Brevo\\Transport\\BrevoTransportFactory::class, \\Symfony\\Component\\Mailer\\Bridge\\Google\\Transport\\GmailTransportFactory::class, \\Symfony\\Component\\Mailer\\Bridge\\Infobip\\Transport\\InfobipTransportFactory::class, \\Symfony\\Component\\Mailer\\Bridge\\MailerSend\\Transport\\MailerSendTransportFactory::class, \\Symfony\\Component\\Mailer\\Bridge\\Mailgun\\Transport\\MailgunTransportFactory::class, \\Symfony\\Component\\Mailer\\Bridge\\Mailjet\\Transport\\MailjetTransportFactory::class, \\Symfony\\Component\\Mailer\\Bridge\\Mailomat\\Transport\\MailomatTransportFactory::class, \\Symfony\\Component\\Mailer\\Bridge\\MailPace\\Transport\\MailPaceTransportFactory::class, \\Symfony\\Component\\Mailer\\Bridge\\Mailchimp\\Transport\\MandrillTransportFactory::class, \\Symfony\\Component\\Mailer\\Bridge\\Postal\\Transport\\PostalTransportFactory::class, \\Symfony\\Component\\Mailer\\Bridge\\Postmark\\Transport\\PostmarkTransportFactory::class, \\Symfony\\Component\\Mailer\\Bridge\\Mailtrap\\Transport\\MailtrapTransportFactory::class, \\Symfony\\Component\\Mailer\\Bridge\\Resend\\Transport\\ResendTransportFactory::class, \\Symfony\\Component\\Mailer\\Bridge\\Scaleway\\Transport\\ScalewayTransportFactory::class, \\Symfony\\Component\\Mailer\\Bridge\\Sendgrid\\Transport\\SendgridTransportFactory::class, \\Symfony\\Component\\Mailer\\Bridge\\Amazon\\Transport\\SesTransportFactory::class, \\Symfony\\Component\\Mailer\\Bridge\\Sweego\\Transport\\SweegoTransportFactory::class]',
          'attributes' => 
          array (
            'startLine' => 55,
            'endLine' => 75,
            'startTokenPos' => 197,
            'startFilePos' => 2806,
            'endTokenPos' => 294,
            'endFilePos' => 3569,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 55,
        'endLine' => 75,
        'startColumn' => 5,
        'endColumn' => 6,
      ),
    ),
    'immediateProperties' => 
    array (
      'factories' => 
      array (
        'declaringClassName' => 'Symfony\\Component\\Mailer\\Transport',
        'implementingClassName' => 'Symfony\\Component\\Mailer\\Transport',
        'name' => 'factories',
        'modifiers' => 4,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'iterable',
            'isIdentifier' => true,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 95,
        'endLine' => 95,
        'startColumn' => 9,
        'endColumn' => 35,
        'isPromoted' => true,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
    ),
    'immediateMethods' => 
    array (
      'fromDsn' => 
      array (
        'name' => 'fromDsn',
        'parameters' => 
        array (
          'dsn' => 
          array (
            'name' => 'dsn',
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
            'startLine' => 77,
            'endLine' => 77,
            'startColumn' => 36,
            'endColumn' => 69,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'dispatcher' => 
          array (
            'name' => 'dispatcher',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 77,
                'endLine' => 77,
                'startTokenPos' => 321,
                'startFilePos' => 3684,
                'endTokenPos' => 321,
                'endFilePos' => 3687,
              ),
            ),
            'type' => 
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
                      'name' => 'Psr\\EventDispatcher\\EventDispatcherInterface',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'null',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 77,
            'endLine' => 77,
            'startColumn' => 72,
            'endColumn' => 115,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'client' => 
          array (
            'name' => 'client',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 77,
                'endLine' => 77,
                'startTokenPos' => 331,
                'startFilePos' => 3721,
                'endTokenPos' => 331,
                'endFilePos' => 3724,
              ),
            ),
            'type' => 
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
                      'name' => 'Symfony\\Contracts\\HttpClient\\HttpClientInterface',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'null',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 77,
            'endLine' => 77,
            'startColumn' => 118,
            'endColumn' => 152,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'logger' => 
          array (
            'name' => 'logger',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 77,
                'endLine' => 77,
                'startTokenPos' => 341,
                'startFilePos' => 3754,
                'endTokenPos' => 341,
                'endFilePos' => 3757,
              ),
            ),
            'type' => 
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
                      'name' => 'Psr\\Log\\LoggerInterface',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'null',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 77,
            'endLine' => 77,
            'startColumn' => 155,
            'endColumn' => 185,
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
            'name' => 'Symfony\\Component\\Mailer\\Transport\\TransportInterface',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 77,
        'endLine' => 82,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Symfony\\Component\\Mailer',
        'declaringClassName' => 'Symfony\\Component\\Mailer\\Transport',
        'implementingClassName' => 'Symfony\\Component\\Mailer\\Transport',
        'currentClassName' => 'Symfony\\Component\\Mailer\\Transport',
        'aliasName' => NULL,
      ),
      'fromDsns' => 
      array (
        'name' => 'fromDsns',
        'parameters' => 
        array (
          'dsns' => 
          array (
            'name' => 'dsns',
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
              0 => 
              array (
                'name' => 'SensitiveParameter',
                'isRepeated' => false,
                'arguments' => 
                array (
                ),
              ),
            ),
            'startLine' => 84,
            'endLine' => 84,
            'startColumn' => 37,
            'endColumn' => 70,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'dispatcher' => 
          array (
            'name' => 'dispatcher',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 84,
                'endLine' => 84,
                'startTokenPos' => 411,
                'startFilePos' => 4055,
                'endTokenPos' => 411,
                'endFilePos' => 4058,
              ),
            ),
            'type' => 
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
                      'name' => 'Psr\\EventDispatcher\\EventDispatcherInterface',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'null',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 84,
            'endLine' => 84,
            'startColumn' => 73,
            'endColumn' => 116,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'client' => 
          array (
            'name' => 'client',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 84,
                'endLine' => 84,
                'startTokenPos' => 421,
                'startFilePos' => 4092,
                'endTokenPos' => 421,
                'endFilePos' => 4095,
              ),
            ),
            'type' => 
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
                      'name' => 'Symfony\\Contracts\\HttpClient\\HttpClientInterface',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'null',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 84,
            'endLine' => 84,
            'startColumn' => 119,
            'endColumn' => 153,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'logger' => 
          array (
            'name' => 'logger',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 84,
                'endLine' => 84,
                'startTokenPos' => 431,
                'startFilePos' => 4125,
                'endTokenPos' => 431,
                'endFilePos' => 4128,
              ),
            ),
            'type' => 
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
                      'name' => 'Psr\\Log\\LoggerInterface',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'null',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 84,
            'endLine' => 84,
            'startColumn' => 156,
            'endColumn' => 186,
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
            'name' => 'Symfony\\Component\\Mailer\\Transport\\TransportInterface',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 84,
        'endLine' => 89,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Symfony\\Component\\Mailer',
        'declaringClassName' => 'Symfony\\Component\\Mailer\\Transport',
        'implementingClassName' => 'Symfony\\Component\\Mailer\\Transport',
        'currentClassName' => 'Symfony\\Component\\Mailer\\Transport',
        'aliasName' => NULL,
      ),
      '__construct' => 
      array (
        'name' => '__construct',
        'parameters' => 
        array (
          'factories' => 
          array (
            'name' => 'factories',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'iterable',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 95,
            'endLine' => 95,
            'startColumn' => 9,
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
 * @param TransportFactoryInterface[] $factories
 */',
        'startLine' => 94,
        'endLine' => 97,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\Mailer',
        'declaringClassName' => 'Symfony\\Component\\Mailer\\Transport',
        'implementingClassName' => 'Symfony\\Component\\Mailer\\Transport',
        'currentClassName' => 'Symfony\\Component\\Mailer\\Transport',
        'aliasName' => NULL,
      ),
      'fromStrings' => 
      array (
        'name' => 'fromStrings',
        'parameters' => 
        array (
          'dsns' => 
          array (
            'name' => 'dsns',
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
              0 => 
              array (
                'name' => 'SensitiveParameter',
                'isRepeated' => false,
                'arguments' => 
                array (
                ),
              ),
            ),
            'startLine' => 99,
            'endLine' => 99,
            'startColumn' => 33,
            'endColumn' => 66,
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
            'name' => 'Symfony\\Component\\Mailer\\Transport\\Transports',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 99,
        'endLine' => 107,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\Mailer',
        'declaringClassName' => 'Symfony\\Component\\Mailer\\Transport',
        'implementingClassName' => 'Symfony\\Component\\Mailer\\Transport',
        'currentClassName' => 'Symfony\\Component\\Mailer\\Transport',
        'aliasName' => NULL,
      ),
      'fromString' => 
      array (
        'name' => 'fromString',
        'parameters' => 
        array (
          'dsn' => 
          array (
            'name' => 'dsn',
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
            'startLine' => 109,
            'endLine' => 109,
            'startColumn' => 32,
            'endColumn' => 65,
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
            'name' => 'Symfony\\Component\\Mailer\\Transport\\TransportInterface',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 109,
        'endLine' => 117,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\Mailer',
        'declaringClassName' => 'Symfony\\Component\\Mailer\\Transport',
        'implementingClassName' => 'Symfony\\Component\\Mailer\\Transport',
        'currentClassName' => 'Symfony\\Component\\Mailer\\Transport',
        'aliasName' => NULL,
      ),
      'parseDsn' => 
      array (
        'name' => 'parseDsn',
        'parameters' => 
        array (
          'dsn' => 
          array (
            'name' => 'dsn',
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
            'startLine' => 119,
            'endLine' => 119,
            'startColumn' => 31,
            'endColumn' => 64,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'offset' => 
          array (
            'name' => 'offset',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 119,
                'endLine' => 119,
                'startTokenPos' => 664,
                'startFilePos' => 5154,
                'endTokenPos' => 664,
                'endFilePos' => 5154,
              ),
            ),
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
            'startLine' => 119,
            'endLine' => 119,
            'startColumn' => 67,
            'endColumn' => 81,
            'parameterIndex' => 1,
            'isOptional' => true,
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
        'docComment' => NULL,
        'startLine' => 119,
        'endLine' => 169,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Symfony\\Component\\Mailer',
        'declaringClassName' => 'Symfony\\Component\\Mailer\\Transport',
        'implementingClassName' => 'Symfony\\Component\\Mailer\\Transport',
        'currentClassName' => 'Symfony\\Component\\Mailer\\Transport',
        'aliasName' => NULL,
      ),
      'fromDsnObject' => 
      array (
        'name' => 'fromDsnObject',
        'parameters' => 
        array (
          'dsn' => 
          array (
            'name' => 'dsn',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Symfony\\Component\\Mailer\\Transport\\Dsn',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 171,
            'endLine' => 171,
            'startColumn' => 35,
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
            'name' => 'Symfony\\Component\\Mailer\\Transport\\TransportInterface',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 171,
        'endLine' => 180,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Symfony\\Component\\Mailer',
        'declaringClassName' => 'Symfony\\Component\\Mailer\\Transport',
        'implementingClassName' => 'Symfony\\Component\\Mailer\\Transport',
        'currentClassName' => 'Symfony\\Component\\Mailer\\Transport',
        'aliasName' => NULL,
      ),
      'getDefaultFactories' => 
      array (
        'name' => 'getDefaultFactories',
        'parameters' => 
        array (
          'dispatcher' => 
          array (
            'name' => 'dispatcher',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 185,
                'endLine' => 185,
                'startTokenPos' => 1230,
                'startFilePos' => 7610,
                'endTokenPos' => 1230,
                'endFilePos' => 7613,
              ),
            ),
            'type' => 
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
                      'name' => 'Psr\\EventDispatcher\\EventDispatcherInterface',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'null',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 185,
            'endLine' => 185,
            'startColumn' => 48,
            'endColumn' => 91,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'client' => 
          array (
            'name' => 'client',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 185,
                'endLine' => 185,
                'startTokenPos' => 1240,
                'startFilePos' => 7647,
                'endTokenPos' => 1240,
                'endFilePos' => 7650,
              ),
            ),
            'type' => 
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
                      'name' => 'Symfony\\Contracts\\HttpClient\\HttpClientInterface',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'null',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 185,
            'endLine' => 185,
            'startColumn' => 94,
            'endColumn' => 128,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'logger' => 
          array (
            'name' => 'logger',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 185,
                'endLine' => 185,
                'startTokenPos' => 1250,
                'startFilePos' => 7680,
                'endTokenPos' => 1250,
                'endFilePos' => 7683,
              ),
            ),
            'type' => 
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
                      'name' => 'Psr\\Log\\LoggerInterface',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'null',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 185,
            'endLine' => 185,
            'startColumn' => 131,
            'endColumn' => 161,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Traversable',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return \\Traversable<int, TransportFactoryInterface>
 */',
        'startLine' => 185,
        'endLine' => 200,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => true,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Symfony\\Component\\Mailer',
        'declaringClassName' => 'Symfony\\Component\\Mailer\\Transport',
        'implementingClassName' => 'Symfony\\Component\\Mailer\\Transport',
        'currentClassName' => 'Symfony\\Component\\Mailer\\Transport',
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