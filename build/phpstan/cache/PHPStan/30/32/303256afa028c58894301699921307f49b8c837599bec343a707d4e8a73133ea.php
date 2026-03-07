<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/BankAccount.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Stripe\BankAccount
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-48e1567ac86aebfa0d2d614eb6620320673f79a8e37885fa955b0704823cec2f-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Stripe\\BankAccount',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/BankAccount.php',
      ),
    ),
    'namespace' => 'Stripe',
    'name' => 'Stripe\\BankAccount',
    'shortName' => 'BankAccount',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * These bank accounts are payment methods on <code>Customer</code> objects.
 *
 * On the other hand <a href="/api#external_accounts">External Accounts</a> are transfer
 * destinations on <code>Account</code> objects for connected accounts.
 * They can be bank accounts or debit cards as well, and are documented in the links above.
 *
 * Related guide: <a href="/payments/bank-debits-transfers">Bank debits and transfers</a>
 *
 * @property string $id Unique identifier for the object.
 * @property string $object String representing the object\'s type. Objects of the same type share the same value.
 * @property null|Account|string $account The account this bank account belongs to. Only applicable on Accounts (not customers or recipients) This property is only available when returned as an <a href="/api/external_account_bank_accounts/object">External Account</a> where <a href="/api/accounts/object#account_object-controller-is_controller">controller.is_controller</a> is <code>true</code>.
 * @property null|string $account_holder_name The name of the person or business that owns the bank account.
 * @property null|string $account_holder_type The type of entity that holds the account. This can be either <code>individual</code> or <code>company</code>.
 * @property null|string $account_type The bank account type. This can only be <code>checking</code> or <code>savings</code> in most countries. In Japan, this can only be <code>futsu</code> or <code>toza</code>.
 * @property null|string[] $available_payout_methods A set of available payout methods for this bank account. Only values from this set should be passed as the <code>method</code> when creating a payout.
 * @property null|string $bank_name Name of the bank associated with the routing number (e.g., <code>WELLS FARGO</code>).
 * @property string $country Two-letter ISO code representing the country the bank account is located in.
 * @property string $currency Three-letter <a href="https://stripe.com/docs/payouts">ISO code for the currency</a> paid out to the bank account.
 * @property null|Customer|string $customer The ID of the customer that the bank account is associated with.
 * @property null|bool $default_for_currency Whether this bank account is the default external account for its currency.
 * @property null|string $fingerprint Uniquely identifies this particular bank account. You can use this attribute to check whether two bank accounts are the same.
 * @property null|(object{currently_due: null|string[], errors: null|(object{code: string, reason: string, requirement: string}&StripeObject)[], past_due: null|string[], pending_verification: null|string[]}&StripeObject) $future_requirements Information about the <a href="https://stripe.com/docs/connect/custom-accounts/future-requirements">upcoming new requirements for the bank account</a>, including what information needs to be collected, and by when.
 * @property string $last4 The last four digits of the bank account number.
 * @property null|StripeObject $metadata Set of <a href="https://stripe.com/docs/api/metadata">key-value pairs</a> that you can attach to an object. This can be useful for storing additional information about the object in a structured format.
 * @property null|(object{currently_due: null|string[], errors: null|(object{code: string, reason: string, requirement: string}&StripeObject)[], past_due: null|string[], pending_verification: null|string[]}&StripeObject) $requirements Information about the requirements for the bank account, including what information needs to be collected.
 * @property null|string $routing_number The routing transit number for the bank account.
 * @property string $status <p>For bank accounts, possible values are <code>new</code>, <code>validated</code>, <code>verified</code>, <code>verification_failed</code>, or <code>errored</code>. A bank account that hasn\'t had any activity or validation performed is <code>new</code>. If Stripe can determine that the bank account exists, its status will be <code>validated</code>. Note that there often isn’t enough information to know (e.g., for smaller credit unions), and the validation is not always run. If customer bank account verification has succeeded, the bank account status will be <code>verified</code>. If the verification failed for any reason, such as microdeposit failure, the status will be <code>verification_failed</code>. If a payout sent to this bank account fails, we\'ll set the status to <code>errored</code> and will not continue to send <a href="https://stripe.com/docs/payouts#payout-schedule">scheduled payouts</a> until the bank details are updated.</p><p>For external accounts, possible values are <code>new</code>, <code>errored</code> and <code>verification_failed</code>. If a payout fails, the status is set to <code>errored</code> and scheduled payouts are stopped until account details are updated. In the US and India, if we can\'t <a href="https://support.stripe.com/questions/bank-account-ownership-verification">verify the owner of the bank account</a>, we\'ll set the status to <code>verification_failed</code>. Other validations aren\'t run against external accounts because they\'re only used for payouts. This means the other statuses don\'t apply.</p>
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 36,
    'endLine' => 171,
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
        'declaringClassName' => 'Stripe\\BankAccount',
        'implementingClassName' => 'Stripe\\BankAccount',
        'name' => 'OBJECT_NAME',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'bank_account\'',
          'attributes' => 
          array (
            'startLine' => 38,
            'endLine' => 38,
            'startTokenPos' => 27,
            'startFilePos' => 5387,
            'endTokenPos' => 27,
            'endFilePos' => 5400,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 38,
        'endLine' => 38,
        'startColumn' => 5,
        'endColumn' => 39,
      ),
      'STATUS_NEW' => 
      array (
        'declaringClassName' => 'Stripe\\BankAccount',
        'implementingClassName' => 'Stripe\\BankAccount',
        'name' => 'STATUS_NEW',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'new\'',
          'attributes' => 
          array (
            'startLine' => 66,
            'endLine' => 66,
            'startTokenPos' => 126,
            'startFilePos' => 6218,
            'endTokenPos' => 126,
            'endFilePos' => 6222,
          ),
        ),
        'docComment' => '/**
 * Possible string representations of the bank verification status.
 *
 * @see https://stripe.com/docs/api/external_account_bank_accounts/object#account_bank_account_object-status
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 66,
        'endLine' => 66,
        'startColumn' => 5,
        'endColumn' => 29,
      ),
      'STATUS_VALIDATED' => 
      array (
        'declaringClassName' => 'Stripe\\BankAccount',
        'implementingClassName' => 'Stripe\\BankAccount',
        'name' => 'STATUS_VALIDATED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'validated\'',
          'attributes' => 
          array (
            'startLine' => 67,
            'endLine' => 67,
            'startTokenPos' => 135,
            'startFilePos' => 6254,
            'endTokenPos' => 135,
            'endFilePos' => 6264,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 67,
        'endLine' => 67,
        'startColumn' => 5,
        'endColumn' => 41,
      ),
      'STATUS_VERIFIED' => 
      array (
        'declaringClassName' => 'Stripe\\BankAccount',
        'implementingClassName' => 'Stripe\\BankAccount',
        'name' => 'STATUS_VERIFIED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'verified\'',
          'attributes' => 
          array (
            'startLine' => 68,
            'endLine' => 68,
            'startTokenPos' => 144,
            'startFilePos' => 6295,
            'endTokenPos' => 144,
            'endFilePos' => 6304,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 68,
        'endLine' => 68,
        'startColumn' => 5,
        'endColumn' => 39,
      ),
      'STATUS_VERIFICATION_FAILED' => 
      array (
        'declaringClassName' => 'Stripe\\BankAccount',
        'implementingClassName' => 'Stripe\\BankAccount',
        'name' => 'STATUS_VERIFICATION_FAILED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'verification_failed\'',
          'attributes' => 
          array (
            'startLine' => 69,
            'endLine' => 69,
            'startTokenPos' => 153,
            'startFilePos' => 6346,
            'endTokenPos' => 153,
            'endFilePos' => 6366,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 69,
        'endLine' => 69,
        'startColumn' => 5,
        'endColumn' => 61,
      ),
      'STATUS_ERRORED' => 
      array (
        'declaringClassName' => 'Stripe\\BankAccount',
        'implementingClassName' => 'Stripe\\BankAccount',
        'name' => 'STATUS_ERRORED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'errored\'',
          'attributes' => 
          array (
            'startLine' => 70,
            'endLine' => 70,
            'startTokenPos' => 162,
            'startFilePos' => 6396,
            'endTokenPos' => 162,
            'endFilePos' => 6404,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 70,
        'endLine' => 70,
        'startColumn' => 5,
        'endColumn' => 37,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'delete' => 
      array (
        'name' => 'delete',
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
                'startLine' => 50,
                'endLine' => 50,
                'startTokenPos' => 42,
                'startFilePos' => 5725,
                'endTokenPos' => 42,
                'endFilePos' => 5728,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 50,
            'endLine' => 50,
            'startColumn' => 28,
            'endColumn' => 41,
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
                'startLine' => 50,
                'endLine' => 50,
                'startTokenPos' => 49,
                'startFilePos' => 5739,
                'endTokenPos' => 49,
                'endFilePos' => 5742,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 50,
            'endLine' => 50,
            'startColumn' => 44,
            'endColumn' => 55,
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
 * Delete a specified external account for a given account.
 *
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return BankAccount the deleted resource
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 50,
        'endLine' => 59,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\BankAccount',
        'implementingClassName' => 'Stripe\\BankAccount',
        'currentClassName' => 'Stripe\\BankAccount',
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
 * @return string The instance URL for this resource. It needs to be special
 *    cased because it doesn\'t fit into the standard resource pattern.
 */',
        'startLine' => 76,
        'endLine' => 95,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\BankAccount',
        'implementingClassName' => 'Stripe\\BankAccount',
        'currentClassName' => 'Stripe\\BankAccount',
        'aliasName' => NULL,
      ),
      'retrieve' => 
      array (
        'name' => 'retrieve',
        'parameters' => 
        array (
          '_id' => 
          array (
            'name' => '_id',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 103,
            'endLine' => 103,
            'startColumn' => 37,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          '_opts' => 
          array (
            'name' => '_opts',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 103,
                'endLine' => 103,
                'startTokenPos' => 358,
                'startFilePos' => 7492,
                'endTokenPos' => 358,
                'endFilePos' => 7495,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 103,
            'endLine' => 103,
            'startColumn' => 43,
            'endColumn' => 55,
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
 * @param array|string $_id
 * @param null|array|string $_opts
 *
 * @throws Exception\\BadMethodCallException
 */',
        'startLine' => 103,
        'endLine' => 112,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\BankAccount',
        'implementingClassName' => 'Stripe\\BankAccount',
        'currentClassName' => 'Stripe\\BankAccount',
        'aliasName' => NULL,
      ),
      'update' => 
      array (
        'name' => 'update',
        'parameters' => 
        array (
          '_id' => 
          array (
            'name' => '_id',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 121,
            'endLine' => 121,
            'startColumn' => 35,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          '_params' => 
          array (
            'name' => '_params',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 121,
                'endLine' => 121,
                'startTokenPos' => 415,
                'startFilePos' => 8129,
                'endTokenPos' => 415,
                'endFilePos' => 8132,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 121,
            'endLine' => 121,
            'startColumn' => 41,
            'endColumn' => 55,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          '_options' => 
          array (
            'name' => '_options',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 121,
                'endLine' => 121,
                'startTokenPos' => 422,
                'startFilePos' => 8147,
                'endTokenPos' => 422,
                'endFilePos' => 8150,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 121,
            'endLine' => 121,
            'startColumn' => 58,
            'endColumn' => 73,
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
 * @param string $_id
 * @param null|array $_params
 * @param null|array|string $_options
 *
 * @throws Exception\\BadMethodCallException
 */',
        'startLine' => 121,
        'endLine' => 130,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\BankAccount',
        'implementingClassName' => 'Stripe\\BankAccount',
        'currentClassName' => 'Stripe\\BankAccount',
        'aliasName' => NULL,
      ),
      'save' => 
      array (
        'name' => 'save',
        'parameters' => 
        array (
          'opts' => 
          array (
            'name' => 'opts',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 143,
                'endLine' => 143,
                'startTokenPos' => 474,
                'startFilePos' => 8987,
                'endTokenPos' => 474,
                'endFilePos' => 8990,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 143,
            'endLine' => 143,
            'startColumn' => 26,
            'endColumn' => 37,
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
 * @param null|array|string $opts
 *
 * @return static the saved resource
 *
 * @throws Exception\\ApiErrorException if the request fails
 *
 * @deprecated The `save` method is deprecated and will be removed in a
 *     future major version of the library. Use the static method `update`
 *     on the resource instead.
 */',
        'startLine' => 143,
        'endLine' => 153,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\BankAccount',
        'implementingClassName' => 'Stripe\\BankAccount',
        'currentClassName' => 'Stripe\\BankAccount',
        'aliasName' => NULL,
      ),
      'verify' => 
      array (
        'name' => 'verify',
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
                'startLine' => 163,
                'endLine' => 163,
                'startTokenPos' => 580,
                'startFilePos' => 9562,
                'endTokenPos' => 580,
                'endFilePos' => 9565,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 163,
            'endLine' => 163,
            'startColumn' => 28,
            'endColumn' => 41,
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
                'startLine' => 163,
                'endLine' => 163,
                'startTokenPos' => 587,
                'startFilePos' => 9576,
                'endTokenPos' => 587,
                'endFilePos' => 9579,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 163,
            'endLine' => 163,
            'startColumn' => 44,
            'endColumn' => 55,
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
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return BankAccount the verified bank account
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 163,
        'endLine' => 170,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\BankAccount',
        'implementingClassName' => 'Stripe\\BankAccount',
        'currentClassName' => 'Stripe\\BankAccount',
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