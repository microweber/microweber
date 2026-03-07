<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../mailerlite/mailerlite-api-v2-php-sdk/src/MailerLite.php-PHPStan\BetterReflection\Reflection\ReflectionClass-MailerLiteApi\MailerLite
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-0c392a07eb5a1684e81425b7ae6584118066c4c25f880cbc420d912bf2e8a782-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'MailerLiteApi\\MailerLite',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../mailerlite/mailerlite-api-v2-php-sdk/src/MailerLite.php',
      ),
    ),
    'namespace' => 'MailerLiteApi',
    'name' => 'MailerLiteApi\\MailerLite',
    'shortName' => 'MailerLite',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Class MailerLite
 *
 * @package MailerLiteApi
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 16,
    'endLine' => 127,
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
      'apiKey' => 
      array (
        'declaringClassName' => 'MailerLiteApi\\MailerLite',
        'implementingClassName' => 'MailerLiteApi\\MailerLite',
        'name' => 'apiKey',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * @var null | string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 21,
        'endLine' => 21,
        'startColumn' => 5,
        'endColumn' => 22,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'restClient' => 
      array (
        'declaringClassName' => 'MailerLiteApi\\MailerLite',
        'implementingClassName' => 'MailerLiteApi\\MailerLite',
        'name' => 'restClient',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * @var RestClient
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 26,
        'endLine' => 26,
        'startColumn' => 5,
        'endColumn' => 26,
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
          'apiKey' => 
          array (
            'name' => 'apiKey',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 33,
                'endLine' => 33,
                'startTokenPos' => 62,
                'startFilePos' => 536,
                'endTokenPos' => 62,
                'endFilePos' => 539,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 33,
            'endLine' => 33,
            'startColumn' => 9,
            'endColumn' => 22,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'httpClient' => 
          array (
            'name' => 'httpClient',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 34,
                'endLine' => 34,
                'startTokenPos' => 71,
                'startFilePos' => 575,
                'endTokenPos' => 71,
                'endFilePos' => 578,
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
                      'name' => 'Http\\Client\\HttpClient',
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
            'startLine' => 34,
            'endLine' => 34,
            'startColumn' => 9,
            'endColumn' => 37,
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
 * @param string|null $apiKey
 * @param HttpClient $client
 */',
        'startLine' => 32,
        'endLine' => 47,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MailerLiteApi',
        'declaringClassName' => 'MailerLiteApi\\MailerLite',
        'implementingClassName' => 'MailerLiteApi\\MailerLite',
        'currentClassName' => 'MailerLiteApi\\MailerLite',
        'aliasName' => NULL,
      ),
      'groups' => 
      array (
        'name' => 'groups',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return \\MailerLiteApi\\Api\\Groups
 */',
        'startLine' => 52,
        'endLine' => 55,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MailerLiteApi',
        'declaringClassName' => 'MailerLiteApi\\MailerLite',
        'implementingClassName' => 'MailerLiteApi\\MailerLite',
        'currentClassName' => 'MailerLiteApi\\MailerLite',
        'aliasName' => NULL,
      ),
      'fields' => 
      array (
        'name' => 'fields',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return \\MailerLiteApi\\Api\\Fields
 */',
        'startLine' => 60,
        'endLine' => 63,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MailerLiteApi',
        'declaringClassName' => 'MailerLiteApi\\MailerLite',
        'implementingClassName' => 'MailerLiteApi\\MailerLite',
        'currentClassName' => 'MailerLiteApi\\MailerLite',
        'aliasName' => NULL,
      ),
      'subscribers' => 
      array (
        'name' => 'subscribers',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return \\MailerLiteApi\\Api\\Subscribers
 */',
        'startLine' => 68,
        'endLine' => 71,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MailerLiteApi',
        'declaringClassName' => 'MailerLiteApi\\MailerLite',
        'implementingClassName' => 'MailerLiteApi\\MailerLite',
        'currentClassName' => 'MailerLiteApi\\MailerLite',
        'aliasName' => NULL,
      ),
      'campaigns' => 
      array (
        'name' => 'campaigns',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return \\MailerLiteApi\\Api\\Campaigns
 */',
        'startLine' => 76,
        'endLine' => 79,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MailerLiteApi',
        'declaringClassName' => 'MailerLiteApi\\MailerLite',
        'implementingClassName' => 'MailerLiteApi\\MailerLite',
        'currentClassName' => 'MailerLiteApi\\MailerLite',
        'aliasName' => NULL,
      ),
      'stats' => 
      array (
        'name' => 'stats',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return \\MailerLiteApi\\Api\\Stats
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
        'namespace' => 'MailerLiteApi',
        'declaringClassName' => 'MailerLiteApi\\MailerLite',
        'implementingClassName' => 'MailerLiteApi\\MailerLite',
        'currentClassName' => 'MailerLiteApi\\MailerLite',
        'aliasName' => NULL,
      ),
      'settings' => 
      array (
        'name' => 'settings',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return \\MailerLiteApi\\Api\\Settings
 */',
        'startLine' => 92,
        'endLine' => 95,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MailerLiteApi',
        'declaringClassName' => 'MailerLiteApi\\MailerLite',
        'implementingClassName' => 'MailerLiteApi\\MailerLite',
        'currentClassName' => 'MailerLiteApi\\MailerLite',
        'aliasName' => NULL,
      ),
      'woocommerce' => 
      array (
        'name' => 'woocommerce',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 97,
        'endLine' => 100,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MailerLiteApi',
        'declaringClassName' => 'MailerLiteApi\\MailerLite',
        'implementingClassName' => 'MailerLiteApi\\MailerLite',
        'currentClassName' => 'MailerLiteApi\\MailerLite',
        'aliasName' => NULL,
      ),
      'segments' => 
      array (
        'name' => 'segments',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return \\MailerLiteApi\\Api\\Segments
 */',
        'startLine' => 105,
        'endLine' => 108,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MailerLiteApi',
        'declaringClassName' => 'MailerLiteApi\\MailerLite',
        'implementingClassName' => 'MailerLiteApi\\MailerLite',
        'currentClassName' => 'MailerLiteApi\\MailerLite',
        'aliasName' => NULL,
      ),
      'batch' => 
      array (
        'name' => 'batch',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return \\MailerLiteApi\\Api\\Batch
 */',
        'startLine' => 113,
        'endLine' => 116,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MailerLiteApi',
        'declaringClassName' => 'MailerLiteApi\\MailerLite',
        'implementingClassName' => 'MailerLiteApi\\MailerLite',
        'currentClassName' => 'MailerLiteApi\\MailerLite',
        'aliasName' => NULL,
      ),
      'getBaseUrl' => 
      array (
        'name' => 'getBaseUrl',
        'parameters' => 
        array (
          'version' => 
          array (
            'name' => 'version',
            'default' => 
            array (
              'code' => '\\MailerLiteApi\\Common\\ApiConstants::VERSION',
              'attributes' => 
              array (
                'startLine' => 122,
                'endLine' => 122,
                'startTokenPos' => 381,
                'startFilePos' => 2448,
                'endTokenPos' => 383,
                'endFilePos' => 2468,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 122,
            'endLine' => 122,
            'startColumn' => 32,
            'endColumn' => 63,
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
 * @param  string $version
 * @return string
 */',
        'startLine' => 122,
        'endLine' => 125,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MailerLiteApi',
        'declaringClassName' => 'MailerLiteApi\\MailerLite',
        'implementingClassName' => 'MailerLiteApi\\MailerLite',
        'currentClassName' => 'MailerLiteApi\\MailerLite',
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