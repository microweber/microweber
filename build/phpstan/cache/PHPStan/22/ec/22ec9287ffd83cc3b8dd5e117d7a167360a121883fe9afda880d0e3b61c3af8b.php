<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/ApiResource.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Stripe\ApiResource
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-37048783c9329fd5e9f25d042743ef159aa27c0240bcdc6c1ace795cbe3f0a36-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Stripe\\ApiResource',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/ApiResource.php',
      ),
    ),
    'namespace' => 'Stripe',
    'name' => 'Stripe\\ApiResource',
    'shortName' => 'ApiResource',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 64,
    'docComment' => '/**
 * Class ApiResource.
 *
 * */',
    'attributes' => 
    array (
    ),
    'startLine' => 9,
    'endLine' => 123,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Stripe\\StripeObject',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Stripe\\ApiOperations\\Request',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'saveWithParent' => 
      array (
        'declaringClassName' => 'Stripe\\ApiResource',
        'implementingClassName' => 'Stripe\\ApiResource',
        'name' => 'saveWithParent',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 37,
            'endLine' => 37,
            'startTokenPos' => 89,
            'startFilePos' => 1204,
            'endTokenPos' => 89,
            'endFilePos' => 1208,
          ),
        ),
        'docComment' => '/**
 * @var bool A flag that can be set a behavior that will cause this
 * resource to be encoded and sent up along with an update of its parent
 * resource. This is usually not desirable because resources are updated
 * individually on their own endpoints, but there are certain cases,
 * replacing a customer\'s source for example, where this is allowed.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 37,
        'endLine' => 37,
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
      'getSavedNestedResources' => 
      array (
        'name' => 'getSavedNestedResources',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return Util\\Set A list of fields that can be their own type of
 * API resource (say a nested card under an account for example), and if
 * that resource is set, it should be transmitted to the API on a create or
 * update. Doing so is not the default behavior because API resources
 * should normally be persisted on their own RESTful endpoints.
 */',
        'startLine' => 20,
        'endLine' => 28,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\ApiResource',
        'implementingClassName' => 'Stripe\\ApiResource',
        'currentClassName' => 'Stripe\\ApiResource',
        'aliasName' => NULL,
      ),
      '__set' => 
      array (
        'name' => '__set',
        'parameters' => 
        array (
          'k' => 
          array (
            'name' => 'k',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 39,
            'endLine' => 39,
            'startColumn' => 27,
            'endColumn' => 28,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'v' => 
          array (
            'name' => 'v',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 39,
            'endLine' => 39,
            'startColumn' => 31,
            'endColumn' => 32,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 39,
        'endLine' => 47,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\ApiResource',
        'implementingClassName' => 'Stripe\\ApiResource',
        'currentClassName' => 'Stripe\\ApiResource',
        'aliasName' => NULL,
      ),
      'refresh' => 
      array (
        'name' => 'refresh',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return ApiResource the refreshed resource
 *
 * @throws Exception\\ApiErrorException
 */',
        'startLine' => 54,
        'endLine' => 69,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\ApiResource',
        'implementingClassName' => 'Stripe\\ApiResource',
        'currentClassName' => 'Stripe\\ApiResource',
        'aliasName' => NULL,
      ),
      'baseUrl' => 
      array (
        'name' => 'baseUrl',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return string the base URL for the given class
 */',
        'startLine' => 74,
        'endLine' => 77,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\ApiResource',
        'implementingClassName' => 'Stripe\\ApiResource',
        'currentClassName' => 'Stripe\\ApiResource',
        'aliasName' => NULL,
      ),
      'classUrl' => 
      array (
        'name' => 'classUrl',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return string the endpoint URL for the given class
 */',
        'startLine' => 82,
        'endLine' => 91,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\ApiResource',
        'implementingClassName' => 'Stripe\\ApiResource',
        'currentClassName' => 'Stripe\\ApiResource',
        'aliasName' => NULL,
      ),
      'resourceUrl' => 
      array (
        'name' => 'resourceUrl',
        'parameters' => 
        array (
          'id' => 
          array (
            'name' => 'id',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 100,
            'endLine' => 100,
            'startColumn' => 40,
            'endColumn' => 42,
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
 * @param null|string $id the ID of the resource
 *
 * @return string the instance endpoint URL for the given class
 *
 * @throws Exception\\UnexpectedValueException if $id is null
 */',
        'startLine' => 100,
        'endLine' => 114,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\ApiResource',
        'implementingClassName' => 'Stripe\\ApiResource',
        'currentClassName' => 'Stripe\\ApiResource',
        'aliasName' => NULL,
      ),
      'instanceUrl' => 
      array (
        'name' => 'instanceUrl',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return string the full API URL for this API resource
 */',
        'startLine' => 119,
        'endLine' => 122,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\ApiResource',
        'implementingClassName' => 'Stripe\\ApiResource',
        'currentClassName' => 'Stripe\\ApiResource',
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