<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/Exception/InvalidRequestException.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Stripe\Exception\InvalidRequestException
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-41d9e55fc93f7f1f0bc4798f04a58e3e0ddd0cd354be974003e907f2afd6e967-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Stripe\\Exception\\InvalidRequestException',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/Exception/InvalidRequestException.php',
      ),
    ),
    'namespace' => 'Stripe\\Exception',
    'name' => 'Stripe\\Exception\\InvalidRequestException',
    'shortName' => 'InvalidRequestException',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * InvalidRequestException is thrown when a request is initiated with invalid
 * parameters.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 9,
    'endLine' => 60,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Stripe\\Exception\\ApiErrorException',
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
      'stripeParam' => 
      array (
        'declaringClassName' => 'Stripe\\Exception\\InvalidRequestException',
        'implementingClassName' => 'Stripe\\Exception\\InvalidRequestException',
        'name' => 'stripeParam',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 11,
        'endLine' => 11,
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
      'factory' => 
      array (
        'name' => 'factory',
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
            'startLine' => 27,
            'endLine' => 27,
            'startColumn' => 9,
            'endColumn' => 16,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'httpStatus' => 
          array (
            'name' => 'httpStatus',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 28,
                'endLine' => 28,
                'startTokenPos' => 42,
                'startFilePos' => 886,
                'endTokenPos' => 42,
                'endFilePos' => 889,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 28,
            'endLine' => 28,
            'startColumn' => 9,
            'endColumn' => 26,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'httpBody' => 
          array (
            'name' => 'httpBody',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 29,
                'endLine' => 29,
                'startTokenPos' => 49,
                'startFilePos' => 912,
                'endTokenPos' => 49,
                'endFilePos' => 915,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 29,
            'endLine' => 29,
            'startColumn' => 9,
            'endColumn' => 24,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'jsonBody' => 
          array (
            'name' => 'jsonBody',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 30,
                'endLine' => 30,
                'startTokenPos' => 56,
                'startFilePos' => 938,
                'endTokenPos' => 56,
                'endFilePos' => 941,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 30,
            'endLine' => 30,
            'startColumn' => 9,
            'endColumn' => 24,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'httpHeaders' => 
          array (
            'name' => 'httpHeaders',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 31,
                'endLine' => 31,
                'startTokenPos' => 63,
                'startFilePos' => 967,
                'endTokenPos' => 63,
                'endFilePos' => 970,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 31,
            'endLine' => 31,
            'startColumn' => 9,
            'endColumn' => 27,
            'parameterIndex' => 4,
            'isOptional' => true,
          ),
          'stripeCode' => 
          array (
            'name' => 'stripeCode',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 32,
                'endLine' => 32,
                'startTokenPos' => 70,
                'startFilePos' => 995,
                'endTokenPos' => 70,
                'endFilePos' => 998,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 32,
            'endLine' => 32,
            'startColumn' => 9,
            'endColumn' => 26,
            'parameterIndex' => 5,
            'isOptional' => true,
          ),
          'stripeParam' => 
          array (
            'name' => 'stripeParam',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 33,
                'endLine' => 33,
                'startTokenPos' => 77,
                'startFilePos' => 1024,
                'endTokenPos' => 77,
                'endFilePos' => 1027,
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
            'endColumn' => 27,
            'parameterIndex' => 6,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Creates a new InvalidRequestException exception.
 *
 * @param string $message the exception message
 * @param null|int $httpStatus the HTTP status code
 * @param null|string $httpBody the HTTP body as a string
 * @param null|array $jsonBody the JSON deserialized body
 * @param null|array|\\Stripe\\Util\\CaseInsensitiveArray $httpHeaders the HTTP headers array
 * @param null|string $stripeCode the Stripe error code
 * @param null|string $stripeParam the parameter related to the error
 *
 * @return InvalidRequestException
 */',
        'startLine' => 26,
        'endLine' => 39,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe\\Exception',
        'declaringClassName' => 'Stripe\\Exception\\InvalidRequestException',
        'implementingClassName' => 'Stripe\\Exception\\InvalidRequestException',
        'currentClassName' => 'Stripe\\Exception\\InvalidRequestException',
        'aliasName' => NULL,
      ),
      'getStripeParam' => 
      array (
        'name' => 'getStripeParam',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Gets the parameter related to the error.
 *
 * @return null|string
 */',
        'startLine' => 46,
        'endLine' => 49,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe\\Exception',
        'declaringClassName' => 'Stripe\\Exception\\InvalidRequestException',
        'implementingClassName' => 'Stripe\\Exception\\InvalidRequestException',
        'currentClassName' => 'Stripe\\Exception\\InvalidRequestException',
        'aliasName' => NULL,
      ),
      'setStripeParam' => 
      array (
        'name' => 'setStripeParam',
        'parameters' => 
        array (
          'stripeParam' => 
          array (
            'name' => 'stripeParam',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 56,
            'endLine' => 56,
            'startColumn' => 36,
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
 * Sets the parameter related to the error.
 *
 * @param null|string $stripeParam
 */',
        'startLine' => 56,
        'endLine' => 59,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe\\Exception',
        'declaringClassName' => 'Stripe\\Exception\\InvalidRequestException',
        'implementingClassName' => 'Stripe\\Exception\\InvalidRequestException',
        'currentClassName' => 'Stripe\\Exception\\InvalidRequestException',
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