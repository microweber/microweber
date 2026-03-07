<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/cashier/src/Concerns/InteractsWithPaymentBehavior.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Laravel\Cashier\Concerns\InteractsWithPaymentBehavior
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-7c33d57494aa76c2c66f8cc8dd023191e9279ca039894dd514d93e1b539664e0-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Laravel\\Cashier\\Concerns\\InteractsWithPaymentBehavior',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../laravel/cashier/src/Concerns/InteractsWithPaymentBehavior.php',
      ),
    ),
    'namespace' => 'Laravel\\Cashier\\Concerns',
    'name' => 'Laravel\\Cashier\\Concerns\\InteractsWithPaymentBehavior',
    'shortName' => 'InteractsWithPaymentBehavior',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 7,
    'endLine' => 86,
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
      'paymentBehavior' => 
      array (
        'declaringClassName' => 'Laravel\\Cashier\\Concerns\\InteractsWithPaymentBehavior',
        'implementingClassName' => 'Laravel\\Cashier\\Concerns\\InteractsWithPaymentBehavior',
        'name' => 'paymentBehavior',
        'modifiers' => 2,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => '\\Stripe\\Subscription::PAYMENT_BEHAVIOR_DEFAULT_INCOMPLETE',
          'attributes' => 
          array (
            'startLine' => 14,
            'endLine' => 14,
            'startTokenPos' => 32,
            'startFilePos' => 273,
            'endTokenPos' => 34,
            'endFilePos' => 327,
          ),
        ),
        'docComment' => '/**
 * Set the payment behavior for any subscription updates.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 14,
        'endLine' => 14,
        'startColumn' => 5,
        'endColumn' => 96,
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
      'defaultIncomplete' => 
      array (
        'name' => 'defaultIncomplete',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Set any new subscription as incomplete when created.
 *
 * @return $this
 */',
        'startLine' => 21,
        'endLine' => 26,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier\\Concerns',
        'declaringClassName' => 'Laravel\\Cashier\\Concerns\\InteractsWithPaymentBehavior',
        'implementingClassName' => 'Laravel\\Cashier\\Concerns\\InteractsWithPaymentBehavior',
        'currentClassName' => 'Laravel\\Cashier\\Concerns\\InteractsWithPaymentBehavior',
        'aliasName' => NULL,
      ),
      'allowPaymentFailures' => 
      array (
        'name' => 'allowPaymentFailures',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Allow subscription changes even if payment fails.
 *
 * @return $this
 */',
        'startLine' => 33,
        'endLine' => 38,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier\\Concerns',
        'declaringClassName' => 'Laravel\\Cashier\\Concerns\\InteractsWithPaymentBehavior',
        'implementingClassName' => 'Laravel\\Cashier\\Concerns\\InteractsWithPaymentBehavior',
        'currentClassName' => 'Laravel\\Cashier\\Concerns\\InteractsWithPaymentBehavior',
        'aliasName' => NULL,
      ),
      'pendingIfPaymentFails' => 
      array (
        'name' => 'pendingIfPaymentFails',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Set any subscription change as pending until payment is successful.
 *
 * @return $this
 */',
        'startLine' => 45,
        'endLine' => 50,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier\\Concerns',
        'declaringClassName' => 'Laravel\\Cashier\\Concerns\\InteractsWithPaymentBehavior',
        'implementingClassName' => 'Laravel\\Cashier\\Concerns\\InteractsWithPaymentBehavior',
        'currentClassName' => 'Laravel\\Cashier\\Concerns\\InteractsWithPaymentBehavior',
        'aliasName' => NULL,
      ),
      'errorIfPaymentFails' => 
      array (
        'name' => 'errorIfPaymentFails',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Prevent any subscription change if payment is unsuccessful.
 *
 * @return $this
 */',
        'startLine' => 57,
        'endLine' => 62,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier\\Concerns',
        'declaringClassName' => 'Laravel\\Cashier\\Concerns\\InteractsWithPaymentBehavior',
        'implementingClassName' => 'Laravel\\Cashier\\Concerns\\InteractsWithPaymentBehavior',
        'currentClassName' => 'Laravel\\Cashier\\Concerns\\InteractsWithPaymentBehavior',
        'aliasName' => NULL,
      ),
      'paymentBehavior' => 
      array (
        'name' => 'paymentBehavior',
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
        'docComment' => '/**
 * Determine the payment behavior when updating the subscription.
 *
 * @return string
 */',
        'startLine' => 69,
        'endLine' => 72,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier\\Concerns',
        'declaringClassName' => 'Laravel\\Cashier\\Concerns\\InteractsWithPaymentBehavior',
        'implementingClassName' => 'Laravel\\Cashier\\Concerns\\InteractsWithPaymentBehavior',
        'currentClassName' => 'Laravel\\Cashier\\Concerns\\InteractsWithPaymentBehavior',
        'aliasName' => NULL,
      ),
      'setPaymentBehavior' => 
      array (
        'name' => 'setPaymentBehavior',
        'parameters' => 
        array (
          'paymentBehavior' => 
          array (
            'name' => 'paymentBehavior',
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
            'startLine' => 80,
            'endLine' => 80,
            'startColumn' => 40,
            'endColumn' => 62,
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
 * Set the payment behavior for any subscription updates.
 *
 * @param  string  $paymentBehavior
 * @return $this
 */',
        'startLine' => 80,
        'endLine' => 85,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Laravel\\Cashier\\Concerns',
        'declaringClassName' => 'Laravel\\Cashier\\Concerns\\InteractsWithPaymentBehavior',
        'implementingClassName' => 'Laravel\\Cashier\\Concerns\\InteractsWithPaymentBehavior',
        'currentClassName' => 'Laravel\\Cashier\\Concerns\\InteractsWithPaymentBehavior',
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