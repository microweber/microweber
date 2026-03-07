<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../mailerlite/mailerlite-api-v2-php-sdk/src/Api/Groups.php-PHPStan\BetterReflection\Reflection\ReflectionClass-MailerLiteApi\Api\Groups
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-cc6416734051e9e0b8dd18ff4c73c34bdac68190cd1e7d4c1c01782745456948-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'MailerLiteApi\\Api\\Groups',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../mailerlite/mailerlite-api-v2-php-sdk/src/Api/Groups.php',
      ),
    ),
    'namespace' => 'MailerLiteApi\\Api',
    'name' => 'MailerLiteApi\\Api\\Groups',
    'shortName' => 'Groups',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Class Groups
 *
 * @package MailerLiteApi\\Api
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 12,
    'endLine' => 110,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'MailerLiteApi\\Common\\ApiAbstract',
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
      'endpoint' => 
      array (
        'declaringClassName' => 'MailerLiteApi\\Api\\Groups',
        'implementingClassName' => 'MailerLiteApi\\Api\\Groups',
        'name' => 'endpoint',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'groups\'',
          'attributes' => 
          array (
            'startLine' => 14,
            'endLine' => 14,
            'startTokenPos' => 30,
            'startFilePos' => 195,
            'endTokenPos' => 30,
            'endFilePos' => 202,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 14,
        'endLine' => 14,
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
      'getSubscribers' => 
      array (
        'name' => 'getSubscribers',
        'parameters' => 
        array (
          'groupId' => 
          array (
            'name' => 'groupId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 23,
            'endLine' => 23,
            'startColumn' => 36,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'type' => 
          array (
            'name' => 'type',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 23,
                'endLine' => 23,
                'startTokenPos' => 48,
                'startFilePos' => 420,
                'endTokenPos' => 48,
                'endFilePos' => 423,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 23,
            'endLine' => 23,
            'startColumn' => 46,
            'endColumn' => 57,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 23,
                'endLine' => 23,
                'startTokenPos' => 55,
                'startFilePos' => 436,
                'endTokenPos' => 56,
                'endFilePos' => 437,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 23,
            'endLine' => 23,
            'startColumn' => 60,
            'endColumn' => 71,
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
 * Get subscribers from group
 * @param  int    $groupId
 * @param  string $type
 * @param  array  $params
 * @return [type]
 */',
        'startLine' => 23,
        'endLine' => 36,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MailerLiteApi\\Api',
        'declaringClassName' => 'MailerLiteApi\\Api\\Groups',
        'implementingClassName' => 'MailerLiteApi\\Api\\Groups',
        'currentClassName' => 'MailerLiteApi\\Api\\Groups',
        'aliasName' => NULL,
      ),
      'getSubscriber' => 
      array (
        'name' => 'getSubscriber',
        'parameters' => 
        array (
          'groupId' => 
          array (
            'name' => 'groupId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 45,
            'endLine' => 45,
            'startColumn' => 35,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'subscriberId' => 
          array (
            'name' => 'subscriberId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 45,
            'endLine' => 45,
            'startColumn' => 45,
            'endColumn' => 57,
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
 * Get single subscriber from group
 *
 * @param $groupId
 * @param $subscriberId
 * @return mixed
 */',
        'startLine' => 45,
        'endLine' => 52,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MailerLiteApi\\Api',
        'declaringClassName' => 'MailerLiteApi\\Api\\Groups',
        'implementingClassName' => 'MailerLiteApi\\Api\\Groups',
        'currentClassName' => 'MailerLiteApi\\Api\\Groups',
        'aliasName' => NULL,
      ),
      'addSubscriber' => 
      array (
        'name' => 'addSubscriber',
        'parameters' => 
        array (
          'groupId' => 
          array (
            'name' => 'groupId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 63,
            'endLine' => 63,
            'startColumn' => 35,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'subscriberData' => 
          array (
            'name' => 'subscriberData',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 63,
                'endLine' => 63,
                'startTokenPos' => 234,
                'startFilePos' => 1405,
                'endTokenPos' => 235,
                'endFilePos' => 1406,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 63,
            'endLine' => 63,
            'startColumn' => 45,
            'endColumn' => 64,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 63,
                'endLine' => 63,
                'startTokenPos' => 242,
                'startFilePos' => 1419,
                'endTokenPos' => 243,
                'endFilePos' => 1420,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 63,
            'endLine' => 63,
            'startColumn' => 67,
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
 * Add single subscriber to group
 *
 * @param int   $groupId
 * @param array $subscriberData
 * @param array $params
 * @return [type]
 */',
        'startLine' => 63,
        'endLine' => 70,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MailerLiteApi\\Api',
        'declaringClassName' => 'MailerLiteApi\\Api\\Groups',
        'implementingClassName' => 'MailerLiteApi\\Api\\Groups',
        'currentClassName' => 'MailerLiteApi\\Api\\Groups',
        'aliasName' => NULL,
      ),
      'removeSubscriber' => 
      array (
        'name' => 'removeSubscriber',
        'parameters' => 
        array (
          'groupId' => 
          array (
            'name' => 'groupId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 79,
            'endLine' => 79,
            'startColumn' => 38,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'subscriberId' => 
          array (
            'name' => 'subscriberId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 79,
            'endLine' => 79,
            'startColumn' => 48,
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
 * Remove subscriber from group
 *
 * @param  int $groupId
 * @param  int $subscriberId
 * @return [type]
 */',
        'startLine' => 79,
        'endLine' => 86,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MailerLiteApi\\Api',
        'declaringClassName' => 'MailerLiteApi\\Api\\Groups',
        'implementingClassName' => 'MailerLiteApi\\Api\\Groups',
        'currentClassName' => 'MailerLiteApi\\Api\\Groups',
        'aliasName' => NULL,
      ),
      'importSubscribers' => 
      array (
        'name' => 'importSubscribers',
        'parameters' => 
        array (
          'groupId' => 
          array (
            'name' => 'groupId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 97,
            'endLine' => 97,
            'startColumn' => 9,
            'endColumn' => 16,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'subscribers' => 
          array (
            'name' => 'subscribers',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 98,
            'endLine' => 98,
            'startColumn' => 9,
            'endColumn' => 20,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'options' => 
          array (
            'name' => 'options',
            'default' => 
            array (
              'code' => '[\'resubscribe\' => false, \'autoresponders\' => false]',
              'attributes' => 
              array (
                'startLine' => 99,
                'endLine' => 102,
                'startTokenPos' => 383,
                'startFilePos' => 2299,
                'endTokenPos' => 398,
                'endFilePos' => 2383,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 99,
            'endLine' => 102,
            'startColumn' => 9,
            'endColumn' => 9,
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
 * Batch add subscribers to group
 *
 * @param  int $groupId
 * @param  array $subscribers
 * @param  array $options
 * @return [type]
 */',
        'startLine' => 96,
        'endLine' => 109,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MailerLiteApi\\Api',
        'declaringClassName' => 'MailerLiteApi\\Api\\Groups',
        'implementingClassName' => 'MailerLiteApi\\Api\\Groups',
        'currentClassName' => 'MailerLiteApi\\Api\\Groups',
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