<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/Util/RequestOptions.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Stripe\Util\RequestOptions
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-d445ba4189ae696ef2bb163f71dc7c45bb2d6e6b9d61bfce64ca6dafa34a89fb-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Stripe\\Util\\RequestOptions',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/Util/RequestOptions.php',
      ),
    ),
    'namespace' => 'Stripe\\Util',
    'name' => 'Stripe\\Util\\RequestOptions',
    'shortName' => 'RequestOptions',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @phpstan-type RequestOptionsArray array{api_key?: string, idempotency_key?: string, stripe_account?: string, stripe_context?: string, stripe_version?: string, api_base?: string, max_network_retries?: int }
 *
 * @psalm-type RequestOptionsArray = array{api_key?: string, idempotency_key?: string, stripe_account?: string, stripe_context?: string, stripe_version?: string, api_base?: string, max_network_retries?: int }
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 10,
    'endLine' => 204,
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
      'HEADERS_TO_PERSIST' => 
      array (
        'declaringClassName' => 'Stripe\\Util\\RequestOptions',
        'implementingClassName' => 'Stripe\\Util\\RequestOptions',
        'name' => 'HEADERS_TO_PERSIST',
        'modifiers' => 17,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'Stripe-Account\', \'Stripe-Version\']',
          'attributes' => 
          array (
            'startLine' => 15,
            'endLine' => 18,
            'startTokenPos' => 25,
            'startFilePos' => 624,
            'endTokenPos' => 33,
            'endFilePos' => 682,
          ),
        ),
        'docComment' => '/**
 * @var array<string> a list of headers that should be persisted across requests
 */',
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
      'headers' => 
      array (
        'declaringClassName' => 'Stripe\\Util\\RequestOptions',
        'implementingClassName' => 'Stripe\\Util\\RequestOptions',
        'name' => 'headers',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/** @var array<string, string> */',
        'attributes' => 
        array (
        ),
        'startLine' => 21,
        'endLine' => 21,
        'startColumn' => 5,
        'endColumn' => 20,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'apiKey' => 
      array (
        'declaringClassName' => 'Stripe\\Util\\RequestOptions',
        'implementingClassName' => 'Stripe\\Util\\RequestOptions',
        'name' => 'apiKey',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/** @var null|string */',
        'attributes' => 
        array (
        ),
        'startLine' => 24,
        'endLine' => 24,
        'startColumn' => 5,
        'endColumn' => 19,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'apiBase' => 
      array (
        'declaringClassName' => 'Stripe\\Util\\RequestOptions',
        'implementingClassName' => 'Stripe\\Util\\RequestOptions',
        'name' => 'apiBase',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/** @var null|string */',
        'attributes' => 
        array (
        ),
        'startLine' => 27,
        'endLine' => 27,
        'startColumn' => 5,
        'endColumn' => 20,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'maxNetworkRetries' => 
      array (
        'declaringClassName' => 'Stripe\\Util\\RequestOptions',
        'implementingClassName' => 'Stripe\\Util\\RequestOptions',
        'name' => 'maxNetworkRetries',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/** @var null|int */',
        'attributes' => 
        array (
        ),
        'startLine' => 30,
        'endLine' => 30,
        'startColumn' => 5,
        'endColumn' => 30,
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
          'key' => 
          array (
            'name' => 'key',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 38,
                'endLine' => 38,
                'startTokenPos' => 76,
                'startFilePos' => 1107,
                'endTokenPos' => 76,
                'endFilePos' => 1110,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 38,
            'endLine' => 38,
            'startColumn' => 33,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'headers' => 
          array (
            'name' => 'headers',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 38,
                'endLine' => 38,
                'startTokenPos' => 83,
                'startFilePos' => 1124,
                'endTokenPos' => 84,
                'endFilePos' => 1125,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 38,
            'endLine' => 38,
            'startColumn' => 46,
            'endColumn' => 58,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'base' => 
          array (
            'name' => 'base',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 38,
                'endLine' => 38,
                'startTokenPos' => 91,
                'startFilePos' => 1136,
                'endTokenPos' => 91,
                'endFilePos' => 1139,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 38,
            'endLine' => 38,
            'startColumn' => 61,
            'endColumn' => 72,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'maxNetworkRetries' => 
          array (
            'name' => 'maxNetworkRetries',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 38,
                'endLine' => 38,
                'startTokenPos' => 98,
                'startFilePos' => 1163,
                'endTokenPos' => 98,
                'endFilePos' => 1166,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 38,
            'endLine' => 38,
            'startColumn' => 75,
            'endColumn' => 99,
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
 * @param null|string $key
 * @param array<string, string> $headers
 * @param null|string $base
 * @param null|int $maxNetworkRetries
 */',
        'startLine' => 38,
        'endLine' => 44,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe\\Util',
        'declaringClassName' => 'Stripe\\Util\\RequestOptions',
        'implementingClassName' => 'Stripe\\Util\\RequestOptions',
        'currentClassName' => 'Stripe\\Util\\RequestOptions',
        'aliasName' => NULL,
      ),
      '__debugInfo' => 
      array (
        'name' => '__debugInfo',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return array<string, string>
 */',
        'startLine' => 49,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe\\Util',
        'declaringClassName' => 'Stripe\\Util\\RequestOptions',
        'implementingClassName' => 'Stripe\\Util\\RequestOptions',
        'currentClassName' => 'Stripe\\Util\\RequestOptions',
        'aliasName' => NULL,
      ),
      'merge' => 
      array (
        'name' => 'merge',
        'parameters' => 
        array (
          'options' => 
          array (
            'name' => 'options',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 68,
            'endLine' => 68,
            'startColumn' => 27,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'strict' => 
          array (
            'name' => 'strict',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 68,
                'endLine' => 68,
                'startTokenPos' => 215,
                'startFilePos' => 2021,
                'endTokenPos' => 215,
                'endFilePos' => 2025,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 68,
            'endLine' => 68,
            'startColumn' => 37,
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
 * Unpacks an options array and merges it into the existing RequestOptions
 * object.
 *
 * @param null|array|RequestOptions|string $options a key => value array
 * @param bool $strict when true, forbid string form and arbitrary keys in array form
 *
 * @return RequestOptions
 */',
        'startLine' => 68,
        'endLine' => 83,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe\\Util',
        'declaringClassName' => 'Stripe\\Util\\RequestOptions',
        'implementingClassName' => 'Stripe\\Util\\RequestOptions',
        'currentClassName' => 'Stripe\\Util\\RequestOptions',
        'aliasName' => NULL,
      ),
      'discardNonPersistentHeaders' => 
      array (
        'name' => 'discardNonPersistentHeaders',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Discards all headers that we don\'t want to persist across requests.
 */',
        'startLine' => 88,
        'endLine' => 95,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe\\Util',
        'declaringClassName' => 'Stripe\\Util\\RequestOptions',
        'implementingClassName' => 'Stripe\\Util\\RequestOptions',
        'currentClassName' => 'Stripe\\Util\\RequestOptions',
        'aliasName' => NULL,
      ),
      'parse' => 
      array (
        'name' => 'parse',
        'parameters' => 
        array (
          'options' => 
          array (
            'name' => 'options',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 107,
            'endLine' => 107,
            'startColumn' => 34,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'strict' => 
          array (
            'name' => 'strict',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 107,
                'endLine' => 107,
                'startTokenPos' => 426,
                'startFilePos' => 3325,
                'endTokenPos' => 426,
                'endFilePos' => 3329,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 107,
            'endLine' => 107,
            'startColumn' => 44,
            'endColumn' => 58,
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
 * Unpacks an options array into an RequestOptions object.
 *
 * @param null|array|RequestOptions|string $options a key => value array
 * @param bool $strict when true, forbid string form and arbitrary keys in array form
 *
 * @return RequestOptions
 *
 * @throws \\Stripe\\Exception\\InvalidArgumentException
 */',
        'startLine' => 107,
        'endLine' => 186,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe\\Util',
        'declaringClassName' => 'Stripe\\Util\\RequestOptions',
        'implementingClassName' => 'Stripe\\Util\\RequestOptions',
        'currentClassName' => 'Stripe\\Util\\RequestOptions',
        'aliasName' => NULL,
      ),
      'redactedApiKey' => 
      array (
        'name' => 'redactedApiKey',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/** @return string */',
        'startLine' => 189,
        'endLine' => 203,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Stripe\\Util',
        'declaringClassName' => 'Stripe\\Util\\RequestOptions',
        'implementingClassName' => 'Stripe\\Util\\RequestOptions',
        'currentClassName' => 'Stripe\\Util\\RequestOptions',
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