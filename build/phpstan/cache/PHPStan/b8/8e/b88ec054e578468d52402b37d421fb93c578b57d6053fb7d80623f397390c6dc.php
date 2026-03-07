<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/Billing/CreditBalanceTransaction.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Stripe\Billing\CreditBalanceTransaction
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-2a037b8c8e9be6a5896f36a064ae837c8b43bd28acf0d15c5368ab9e43dbdab6-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Stripe\\Billing\\CreditBalanceTransaction',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/Billing/CreditBalanceTransaction.php',
      ),
    ),
    'namespace' => 'Stripe\\Billing',
    'name' => 'Stripe\\Billing\\CreditBalanceTransaction',
    'shortName' => 'CreditBalanceTransaction',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A credit balance transaction is a resource representing a transaction (either a credit or a debit) against an existing credit grant.
 *
 * @property string $id Unique identifier for the object.
 * @property string $object String representing the object\'s type. Objects of the same type share the same value.
 * @property int $created Time at which the object was created. Measured in seconds since the Unix epoch.
 * @property null|(object{amount: (object{monetary: null|(object{currency: string, value: int}&\\Stripe\\StripeObject), type: string}&\\Stripe\\StripeObject), credits_application_invoice_voided: null|(object{invoice: string|\\Stripe\\Invoice, invoice_line_item: string}&\\Stripe\\StripeObject), type: string}&\\Stripe\\StripeObject) $credit Credit details for this credit balance transaction. Only present if type is <code>credit</code>.
 * @property CreditGrant|string $credit_grant The credit grant associated with this credit balance transaction.
 * @property null|(object{amount: (object{monetary: null|(object{currency: string, value: int}&\\Stripe\\StripeObject), type: string}&\\Stripe\\StripeObject), credits_applied: null|(object{invoice: string|\\Stripe\\Invoice, invoice_line_item: string}&\\Stripe\\StripeObject), type: string}&\\Stripe\\StripeObject) $debit Debit details for this credit balance transaction. Only present if type is <code>debit</code>.
 * @property int $effective_at The effective time of this credit balance transaction.
 * @property bool $livemode Has the value <code>true</code> if the object exists in live mode or the value <code>false</code> if the object exists in test mode.
 * @property null|string|\\Stripe\\TestHelpers\\TestClock $test_clock ID of the test clock this credit balance transaction belongs to.
 * @property null|string $type The type of credit balance transaction (credit or debit).
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 21,
    'endLine' => 63,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Stripe\\ApiResource',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
      'OBJECT_NAME' => 
      array (
        'declaringClassName' => 'Stripe\\Billing\\CreditBalanceTransaction',
        'implementingClassName' => 'Stripe\\Billing\\CreditBalanceTransaction',
        'name' => 'OBJECT_NAME',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'billing.credit_balance_transaction\'',
          'attributes' => 
          array (
            'startLine' => 23,
            'endLine' => 23,
            'startTokenPos' => 27,
            'startFilePos' => 1999,
            'endTokenPos' => 27,
            'endFilePos' => 2034,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 23,
        'endLine' => 23,
        'startColumn' => 5,
        'endColumn' => 61,
      ),
      'TYPE_CREDIT' => 
      array (
        'declaringClassName' => 'Stripe\\Billing\\CreditBalanceTransaction',
        'implementingClassName' => 'Stripe\\Billing\\CreditBalanceTransaction',
        'name' => 'TYPE_CREDIT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'credit\'',
          'attributes' => 
          array (
            'startLine' => 25,
            'endLine' => 25,
            'startTokenPos' => 36,
            'startFilePos' => 2062,
            'endTokenPos' => 36,
            'endFilePos' => 2069,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 25,
        'endLine' => 25,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_DEBIT' => 
      array (
        'declaringClassName' => 'Stripe\\Billing\\CreditBalanceTransaction',
        'implementingClassName' => 'Stripe\\Billing\\CreditBalanceTransaction',
        'name' => 'TYPE_DEBIT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'debit\'',
          'attributes' => 
          array (
            'startLine' => 26,
            'endLine' => 26,
            'startTokenPos' => 45,
            'startFilePos' => 2095,
            'endTokenPos' => 45,
            'endFilePos' => 2101,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 26,
        'endLine' => 26,
        'startColumn' => 5,
        'endColumn' => 31,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'all' => 
      array (
        'name' => 'all',
        'parameters' => 
        array (
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 38,
                'endLine' => 38,
                'startTokenPos' => 62,
                'startFilePos' => 2579,
                'endTokenPos' => 62,
                'endFilePos' => 2582,
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
            'startColumn' => 32,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'opts' => 
          array (
            'name' => 'opts',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 38,
                'endLine' => 38,
                'startTokenPos' => 69,
                'startFilePos' => 2593,
                'endTokenPos' => 69,
                'endFilePos' => 2596,
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
            'startColumn' => 48,
            'endColumn' => 59,
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
 * Retrieve a list of credit balance transactions.
 *
 * @param null|array{credit_grant?: string, customer: string, ending_before?: string, expand?: string[], limit?: int, starting_after?: string} $params
 * @param null|array|string $opts
 *
 * @return \\Stripe\\Collection<CreditBalanceTransaction> of ApiResources
 *
 * @throws \\Stripe\\Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 38,
        'endLine' => 43,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe\\Billing',
        'declaringClassName' => 'Stripe\\Billing\\CreditBalanceTransaction',
        'implementingClassName' => 'Stripe\\Billing\\CreditBalanceTransaction',
        'currentClassName' => 'Stripe\\Billing\\CreditBalanceTransaction',
        'aliasName' => NULL,
      ),
      'retrieve' => 
      array (
        'name' => 'retrieve',
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
            'startLine' => 55,
            'endLine' => 55,
            'startColumn' => 37,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'opts' => 
          array (
            'name' => 'opts',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 55,
                'endLine' => 55,
                'startTokenPos' => 125,
                'startFilePos' => 3131,
                'endTokenPos' => 125,
                'endFilePos' => 3134,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 55,
            'endLine' => 55,
            'startColumn' => 42,
            'endColumn' => 53,
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
 * Retrieves a credit balance transaction.
 *
 * @param array|string $id the ID of the API resource to retrieve, or an options array containing an `id` key
 * @param null|array|string $opts
 *
 * @return CreditBalanceTransaction
 *
 * @throws \\Stripe\\Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 55,
        'endLine' => 62,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe\\Billing',
        'declaringClassName' => 'Stripe\\Billing\\CreditBalanceTransaction',
        'implementingClassName' => 'Stripe\\Billing\\CreditBalanceTransaction',
        'currentClassName' => 'Stripe\\Billing\\CreditBalanceTransaction',
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