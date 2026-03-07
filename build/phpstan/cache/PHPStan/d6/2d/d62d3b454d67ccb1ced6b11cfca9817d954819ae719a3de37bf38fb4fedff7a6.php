<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/cashier/src/Concerns/HandlesPaymentFailures.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Laravel\Cashier\Concerns\HandlesPaymentFailures
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-c48b8250843f426e3b7b6c7b84b0965131c0360a485f6359a1297e01f98734a8-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Laravel\\Cashier\\Concerns\\HandlesPaymentFailures',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/cashier/src/Concerns/HandlesPaymentFailures.php',
      ),
    ),
    'namespace' => 'Laravel\\Cashier\\Concerns',
    'name' => 'Laravel\\Cashier\\Concerns\\HandlesPaymentFailures',
    'shortName' => 'HandlesPaymentFailures',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 11,
    'endLine' => 106,
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
      'confirmIncompletePayment' => 
      array (
        'declaringClassName' => 'Laravel\\Cashier\\Concerns\\HandlesPaymentFailures',
        'implementingClassName' => 'Laravel\\Cashier\\Concerns\\HandlesPaymentFailures',
        'name' => 'confirmIncompletePayment',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => 'true',
          'attributes' => 
          array (
            'startLine' => 18,
            'endLine' => 18,
            'startTokenPos' => 56,
            'startFilePos' => 459,
            'endTokenPos' => 56,
            'endFilePos' => 462,
          ),
        ),
        'docComment' => '/**
 * Indicates if incomplete payments should be confirmed automatically.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 18,
        'endLine' => 18,
        'startColumn' => 5,
        'endColumn' => 52,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'paymentConfirmationOptions' => 
      array (
        'declaringClassName' => 'Laravel\\Cashier\\Concerns\\HandlesPaymentFailures',
        'implementingClassName' => 'Laravel\\Cashier\\Concerns\\HandlesPaymentFailures',
        'name' => 'paymentConfirmationOptions',
        'modifiers' => 2,
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
            'startTokenPos' => 69,
            'startFilePos' => 621,
            'endTokenPos' => 70,
            'endFilePos' => 622,
          ),
        ),
        'docComment' => '/**
 * The options to be used when confirming a payment intent.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 25,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 53,
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
      'handlePaymentFailure' => 
      array (
        'name' => 'handlePaymentFailure',
        'parameters' => 
        array (
          'subscription' => 
          array (
            'name' => 'subscription',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Laravel\\Cashier\\Subscription',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 38,
            'endLine' => 38,
            'startColumn' => 42,
            'endColumn' => 67,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'paymentMethod' => 
          array (
            'name' => 'paymentMethod',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 38,
                'endLine' => 38,
                'startTokenPos' => 90,
                'startFilePos' => 1031,
                'endTokenPos' => 90,
                'endFilePos' => 1034,
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
            'startColumn' => 70,
            'endColumn' => 90,
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
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Handle a failed payment for the given subscription.
 *
 * @param  \\Laravel\\Cashier\\Subscription  $subscription
 * @param  \\Stripe\\PaymentMethod|string|null  $paymentMethod
 * @return void
 *
 * @throws \\Laravel\\Cashier\\Exceptions\\IncompletePayment
 *
 * @internal
 */',
        'startLine' => 38,
        'endLine' => 80,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier\\Concerns',
        'declaringClassName' => 'Laravel\\Cashier\\Concerns\\HandlesPaymentFailures',
        'implementingClassName' => 'Laravel\\Cashier\\Concerns\\HandlesPaymentFailures',
        'currentClassName' => 'Laravel\\Cashier\\Concerns\\HandlesPaymentFailures',
        'aliasName' => NULL,
      ),
      'ignoreIncompletePayments' => 
      array (
        'name' => 'ignoreIncompletePayments',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Prevent automatic confirmation of incomplete payments.
 *
 * @return $this
 */',
        'startLine' => 87,
        'endLine' => 92,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier\\Concerns',
        'declaringClassName' => 'Laravel\\Cashier\\Concerns\\HandlesPaymentFailures',
        'implementingClassName' => 'Laravel\\Cashier\\Concerns\\HandlesPaymentFailures',
        'currentClassName' => 'Laravel\\Cashier\\Concerns\\HandlesPaymentFailures',
        'aliasName' => NULL,
      ),
      'withPaymentConfirmationOptions' => 
      array (
        'name' => 'withPaymentConfirmationOptions',
        'parameters' => 
        array (
          'options' => 
          array (
            'name' => 'options',
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
            'startLine' => 100,
            'endLine' => 100,
            'startColumn' => 52,
            'endColumn' => 65,
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
 * Specify the options to be used when confirming a payment intent.
 *
 * @param  array  $options
 * @return $this
 */',
        'startLine' => 100,
        'endLine' => 105,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier\\Concerns',
        'declaringClassName' => 'Laravel\\Cashier\\Concerns\\HandlesPaymentFailures',
        'implementingClassName' => 'Laravel\\Cashier\\Concerns\\HandlesPaymentFailures',
        'currentClassName' => 'Laravel\\Cashier\\Concerns\\HandlesPaymentFailures',
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