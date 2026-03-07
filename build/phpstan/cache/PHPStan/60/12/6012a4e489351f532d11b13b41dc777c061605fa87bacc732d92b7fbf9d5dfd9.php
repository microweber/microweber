<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/Customer.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Stripe\Customer
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-9e807f5929bfb6127736161cb2313525b8040cb5cb1d60b06850be5822a50547-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Stripe\\Customer',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/Customer.php',
      ),
    ),
    'namespace' => 'Stripe',
    'name' => 'Stripe\\Customer',
    'shortName' => 'Customer',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * This object represents a customer of your business. Use it to <a href="https://stripe.com/docs/invoicing/customer">create recurring charges</a>, <a href="https://stripe.com/docs/payments/save-during-payment">save payment</a> and contact information,
 * and track payments that belong to the same customer.
 *
 * @property string $id Unique identifier for the object.
 * @property string $object String representing the object\'s type. Objects of the same type share the same value.
 * @property null|(object{city: null|string, country: null|string, line1: null|string, line2: null|string, postal_code: null|string, state: null|string}&StripeObject) $address The customer\'s address.
 * @property null|int $balance The current balance, if any, that\'s stored on the customer in their default currency. If negative, the customer has credit to apply to their next invoice. If positive, the customer has an amount owed that\'s added to their next invoice. The balance only considers amounts that Stripe hasn\'t successfully applied to any invoice. It doesn\'t reflect unpaid invoices. This balance is only taken into account after invoices finalize. For multi-currency balances, see <a href="https://stripe.com/docs/api/customers/object#customer_object-invoice_credit_balance">invoice_credit_balance</a>.
 * @property null|CashBalance $cash_balance The current funds being held by Stripe on behalf of the customer. You can apply these funds towards payment intents when the source is &quot;cash_balance&quot;. The <code>settings[reconciliation_mode]</code> field describes if these funds apply to these payment intents manually or automatically.
 * @property int $created Time at which the object was created. Measured in seconds since the Unix epoch.
 * @property null|string $currency Three-letter <a href="https://stripe.com/docs/currencies">ISO code for the currency</a> the customer can be charged in for recurring billing purposes.
 * @property null|Account|BankAccount|Card|Source|string $default_source <p>ID of the default payment source for the customer.</p><p>If you use payment methods created through the PaymentMethods API, see the <a href="https://stripe.com/docs/api/customers/object#customer_object-invoice_settings-default_payment_method">invoice_settings.default_payment_method</a> field instead.</p>
 * @property null|bool $delinquent <p>Tracks the most recent state change on any invoice belonging to the customer. Paying an invoice or marking it uncollectible via the API will set this field to false. An automatic payment failure or passing the <code>invoice.due_date</code> will set this field to <code>true</code>.</p><p>If an invoice becomes uncollectible by <a href="https://stripe.com/docs/billing/automatic-collection">dunning</a>, <code>delinquent</code> doesn\'t reset to <code>false</code>.</p><p>If you care whether the customer has paid their most recent subscription invoice, use <code>subscription.status</code> instead. Paying or marking uncollectible any customer invoice regardless of whether it is the latest invoice for a subscription will always set this field to <code>false</code>.</p>
 * @property null|string $description An arbitrary string attached to the object. Often useful for displaying to users.
 * @property null|Discount $discount Describes the current discount active on the customer, if there is one.
 * @property null|string $email The customer\'s email address.
 * @property null|StripeObject $invoice_credit_balance The current multi-currency balances, if any, that\'s stored on the customer. If positive in a currency, the customer has a credit to apply to their next invoice denominated in that currency. If negative, the customer has an amount owed that\'s added to their next invoice denominated in that currency. These balances don\'t apply to unpaid invoices. They solely track amounts that Stripe hasn\'t successfully applied to any invoice. Stripe only applies a balance in a specific currency to an invoice after that invoice (which is in the same currency) finalizes.
 * @property null|string $invoice_prefix The prefix for the customer used to generate unique invoice numbers.
 * @property null|(object{custom_fields: null|(object{name: string, value: string}&StripeObject)[], default_payment_method: null|PaymentMethod|string, footer: null|string, rendering_options: null|(object{amount_tax_display: null|string, template: null|string}&StripeObject)}&StripeObject) $invoice_settings
 * @property bool $livemode Has the value <code>true</code> if the object exists in live mode or the value <code>false</code> if the object exists in test mode.
 * @property null|StripeObject $metadata Set of <a href="https://stripe.com/docs/api/metadata">key-value pairs</a> that you can attach to an object. This can be useful for storing additional information about the object in a structured format.
 * @property null|string $name The customer\'s full name or business name.
 * @property null|int $next_invoice_sequence The suffix of the customer\'s next invoice number (for example, 0001). When the account uses account level sequencing, this parameter is ignored in API requests and the field omitted in API responses.
 * @property null|string $phone The customer\'s phone number.
 * @property null|string[] $preferred_locales The customer\'s preferred locales (languages), ordered by preference.
 * @property null|(object{address?: (object{city: null|string, country: null|string, line1: null|string, line2: null|string, postal_code: null|string, state: null|string}&StripeObject), carrier?: null|string, name?: string, phone?: null|string, tracking_number?: null|string}&StripeObject) $shipping Mailing and shipping address for the customer. Appears on invoices emailed to this customer.
 * @property null|Collection<Account|BankAccount|Card|Source> $sources The customer\'s payment sources, if any.
 * @property null|Collection<Subscription> $subscriptions The customer\'s current subscriptions, if any.
 * @property null|(object{automatic_tax: string, ip_address: null|string, location: null|(object{country: string, source: string, state: null|string}&StripeObject)}&StripeObject) $tax
 * @property null|string $tax_exempt Describes the customer\'s tax exemption status, which is <code>none</code>, <code>exempt</code>, or <code>reverse</code>. When set to <code>reverse</code>, invoice and receipt PDFs include the following text: <strong>&quot;Reverse charge&quot;</strong>.
 * @property null|Collection<TaxId> $tax_ids The customer\'s tax IDs.
 * @property null|string|TestHelpers\\TestClock $test_clock ID of the test clock that this customer belongs to.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 40,
    'endLine' => 500,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Stripe\\ApiResource',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Stripe\\ApiOperations\\NestedResource',
      1 => 'Stripe\\ApiOperations\\Update',
    ),
    'immediateConstants' => 
    array (
      'OBJECT_NAME' => 
      array (
        'declaringClassName' => 'Stripe\\Customer',
        'implementingClassName' => 'Stripe\\Customer',
        'name' => 'OBJECT_NAME',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'customer\'',
          'attributes' => 
          array (
            'startLine' => 42,
            'endLine' => 42,
            'startTokenPos' => 27,
            'startFilePos' => 6741,
            'endTokenPos' => 27,
            'endFilePos' => 6750,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 42,
        'endLine' => 42,
        'startColumn' => 5,
        'endColumn' => 35,
      ),
      'TAX_EXEMPT_EXEMPT' => 
      array (
        'declaringClassName' => 'Stripe\\Customer',
        'implementingClassName' => 'Stripe\\Customer',
        'name' => 'TAX_EXEMPT_EXEMPT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'exempt\'',
          'attributes' => 
          array (
            'startLine' => 47,
            'endLine' => 47,
            'startTokenPos' => 46,
            'startFilePos' => 6853,
            'endTokenPos' => 46,
            'endFilePos' => 6860,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 47,
        'endLine' => 47,
        'startColumn' => 5,
        'endColumn' => 39,
      ),
      'TAX_EXEMPT_NONE' => 
      array (
        'declaringClassName' => 'Stripe\\Customer',
        'implementingClassName' => 'Stripe\\Customer',
        'name' => 'TAX_EXEMPT_NONE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'none\'',
          'attributes' => 
          array (
            'startLine' => 48,
            'endLine' => 48,
            'startTokenPos' => 55,
            'startFilePos' => 6891,
            'endTokenPos' => 55,
            'endFilePos' => 6896,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 48,
        'endLine' => 48,
        'startColumn' => 5,
        'endColumn' => 35,
      ),
      'TAX_EXEMPT_REVERSE' => 
      array (
        'declaringClassName' => 'Stripe\\Customer',
        'implementingClassName' => 'Stripe\\Customer',
        'name' => 'TAX_EXEMPT_REVERSE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'reverse\'',
          'attributes' => 
          array (
            'startLine' => 49,
            'endLine' => 49,
            'startTokenPos' => 64,
            'startFilePos' => 6930,
            'endTokenPos' => 64,
            'endFilePos' => 6938,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 49,
        'endLine' => 49,
        'startColumn' => 5,
        'endColumn' => 41,
      ),
      'PATH_CASH_BALANCE' => 
      array (
        'declaringClassName' => 'Stripe\\Customer',
        'implementingClassName' => 'Stripe\\Customer',
        'name' => 'PATH_CASH_BALANCE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'/cash_balance\'',
          'attributes' => 
          array (
            'startLine' => 247,
            'endLine' => 247,
            'startTokenPos' => 911,
            'startFilePos' => 15626,
            'endTokenPos' => 911,
            'endFilePos' => 15640,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 247,
        'endLine' => 247,
        'startColumn' => 5,
        'endColumn' => 46,
      ),
      'PATH_BALANCE_TRANSACTIONS' => 
      array (
        'declaringClassName' => 'Stripe\\Customer',
        'implementingClassName' => 'Stripe\\Customer',
        'name' => 'PATH_BALANCE_TRANSACTIONS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'/balance_transactions\'',
          'attributes' => 
          array (
            'startLine' => 278,
            'endLine' => 278,
            'startTokenPos' => 1030,
            'startFilePos' => 16705,
            'endTokenPos' => 1030,
            'endFilePos' => 16727,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 278,
        'endLine' => 278,
        'startColumn' => 5,
        'endColumn' => 62,
      ),
      'PATH_CASH_BALANCE_TRANSACTIONS' => 
      array (
        'declaringClassName' => 'Stripe\\Customer',
        'implementingClassName' => 'Stripe\\Customer',
        'name' => 'PATH_CASH_BALANCE_TRANSACTIONS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'/cash_balance_transactions\'',
          'attributes' => 
          array (
            'startLine' => 337,
            'endLine' => 337,
            'startTokenPos' => 1259,
            'startFilePos' => 19140,
            'endTokenPos' => 1259,
            'endFilePos' => 19167,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 337,
        'endLine' => 337,
        'startColumn' => 5,
        'endColumn' => 72,
      ),
      'PATH_SOURCES' => 
      array (
        'declaringClassName' => 'Stripe\\Customer',
        'implementingClassName' => 'Stripe\\Customer',
        'name' => 'PATH_SOURCES',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'/sources\'',
          'attributes' => 
          array (
            'startLine' => 367,
            'endLine' => 367,
            'startTokenPos' => 1378,
            'startFilePos' => 20466,
            'endTokenPos' => 1378,
            'endFilePos' => 20475,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 367,
        'endLine' => 367,
        'startColumn' => 5,
        'endColumn' => 36,
      ),
      'PATH_TAX_IDS' => 
      array (
        'declaringClassName' => 'Stripe\\Customer',
        'implementingClassName' => 'Stripe\\Customer',
        'name' => 'PATH_TAX_IDS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'/tax_ids\'',
          'attributes' => 
          array (
            'startLine' => 441,
            'endLine' => 441,
            'startTokenPos' => 1665,
            'startFilePos' => 23173,
            'endTokenPos' => 1665,
            'endFilePos' => 23182,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 441,
        'endLine' => 441,
        'startColumn' => 5,
        'endColumn' => 36,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'create' => 
      array (
        'name' => 'create',
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
                'startLine' => 61,
                'endLine' => 61,
                'startTokenPos' => 81,
                'startFilePos' => 8282,
                'endTokenPos' => 81,
                'endFilePos' => 8285,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 61,
            'endLine' => 61,
            'startColumn' => 35,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'options' => 
          array (
            'name' => 'options',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 61,
                'endLine' => 61,
                'startTokenPos' => 88,
                'startFilePos' => 8299,
                'endTokenPos' => 88,
                'endFilePos' => 8302,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 61,
            'endLine' => 61,
            'startColumn' => 51,
            'endColumn' => 65,
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
 * Creates a new customer object.
 *
 * @param null|array{address?: null|array{city?: string, country?: string, line1?: string, line2?: string, postal_code?: string, state?: string}, balance?: int, cash_balance?: array{settings?: array{reconciliation_mode?: string}}, description?: string, email?: string, expand?: string[], invoice_prefix?: string, invoice_settings?: array{custom_fields?: null|array{name: string, value: string}[], default_payment_method?: string, footer?: string, rendering_options?: null|array{amount_tax_display?: null|string, template?: string}}, metadata?: null|array<string, string>, name?: string, next_invoice_sequence?: int, payment_method?: string, phone?: string, preferred_locales?: string[], shipping?: null|array{address: array{city?: string, country?: string, line1?: string, line2?: string, postal_code?: string, state?: string}, name: string, phone?: string}, source?: string, tax?: array{ip_address?: null|string, validate_location?: string}, tax_exempt?: null|string, tax_id_data?: array{type: string, value: string}[], test_clock?: string, validate?: bool} $params
 * @param null|array|string $options
 *
 * @return Customer the created resource
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 61,
        'endLine' => 71,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Customer',
        'implementingClassName' => 'Stripe\\Customer',
        'currentClassName' => 'Stripe\\Customer',
        'aliasName' => NULL,
      ),
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
                'startLine' => 84,
                'endLine' => 84,
                'startTokenPos' => 183,
                'startFilePos' => 9009,
                'endTokenPos' => 183,
                'endFilePos' => 9012,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 84,
            'endLine' => 84,
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
                'startLine' => 84,
                'endLine' => 84,
                'startTokenPos' => 190,
                'startFilePos' => 9023,
                'endTokenPos' => 190,
                'endFilePos' => 9026,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 84,
            'endLine' => 84,
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
 * Permanently deletes a customer. It cannot be undone. Also immediately cancels
 * any active subscriptions on the customer.
 *
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return Customer the deleted resource
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 84,
        'endLine' => 93,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Customer',
        'implementingClassName' => 'Stripe\\Customer',
        'currentClassName' => 'Stripe\\Customer',
        'aliasName' => NULL,
      ),
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
                'startLine' => 106,
                'endLine' => 106,
                'startTokenPos' => 273,
                'startFilePos' => 9823,
                'endTokenPos' => 273,
                'endFilePos' => 9826,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 106,
            'endLine' => 106,
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
                'startLine' => 106,
                'endLine' => 106,
                'startTokenPos' => 280,
                'startFilePos' => 9837,
                'endTokenPos' => 280,
                'endFilePos' => 9840,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 106,
            'endLine' => 106,
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
 * Returns a list of your customers. The customers are returned sorted by creation
 * date, with the most recent customers appearing first.
 *
 * @param null|array{created?: array|int, email?: string, ending_before?: string, expand?: string[], limit?: int, starting_after?: string, test_clock?: string} $params
 * @param null|array|string $opts
 *
 * @return Collection<Customer> of ApiResources
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 106,
        'endLine' => 111,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Customer',
        'implementingClassName' => 'Stripe\\Customer',
        'currentClassName' => 'Stripe\\Customer',
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
            'startLine' => 123,
            'endLine' => 123,
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
                'startLine' => 123,
                'endLine' => 123,
                'startTokenPos' => 336,
                'startFilePos' => 10332,
                'endTokenPos' => 336,
                'endFilePos' => 10335,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 123,
            'endLine' => 123,
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
 * Retrieves a Customer object.
 *
 * @param array|string $id the ID of the API resource to retrieve, or an options array containing an `id` key
 * @param null|array|string $opts
 *
 * @return Customer
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 123,
        'endLine' => 130,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Customer',
        'implementingClassName' => 'Stripe\\Customer',
        'currentClassName' => 'Stripe\\Customer',
        'aliasName' => NULL,
      ),
      'update' => 
      array (
        'name' => 'update',
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
            'startLine' => 155,
            'endLine' => 155,
            'startColumn' => 35,
            'endColumn' => 37,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 155,
                'endLine' => 155,
                'startTokenPos' => 399,
                'startFilePos' => 12812,
                'endTokenPos' => 399,
                'endFilePos' => 12815,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 155,
            'endLine' => 155,
            'startColumn' => 40,
            'endColumn' => 53,
            'parameterIndex' => 1,
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
                'startLine' => 155,
                'endLine' => 155,
                'startTokenPos' => 406,
                'startFilePos' => 12826,
                'endTokenPos' => 406,
                'endFilePos' => 12829,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 155,
            'endLine' => 155,
            'startColumn' => 56,
            'endColumn' => 67,
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
 * Updates the specified customer by setting the values of the parameters passed.
 * Any parameters not provided will be left unchanged. For example, if you pass the
 * <strong>source</strong> parameter, that becomes the customer’s active source
 * (e.g., a card) to be used for all charges in the future. When you update a
 * customer to a new valid card source by passing the <strong>source</strong>
 * parameter: for each of the customer’s current subscriptions, if the subscription
 * bills automatically and is in the <code>past_due</code> state, then the latest
 * open invoice for the subscription with automatic collection enabled will be
 * retried. This retry will not count as an automatic retry, and will not affect
 * the next regularly scheduled payment for the invoice. Changing the
 * <strong>default_source</strong> for a customer will not trigger this behavior.
 *
 * This request accepts mostly the same arguments as the customer creation call.
 *
 * @param string $id the ID of the resource to update
 * @param null|array{address?: null|array{city?: string, country?: string, line1?: string, line2?: string, postal_code?: string, state?: string}, balance?: int, cash_balance?: array{settings?: array{reconciliation_mode?: string}}, default_source?: string, description?: string, email?: string, expand?: string[], invoice_prefix?: string, invoice_settings?: array{custom_fields?: null|array{name: string, value: string}[], default_payment_method?: string, footer?: string, rendering_options?: null|array{amount_tax_display?: null|string, template?: string}}, metadata?: null|array<string, string>, name?: string, next_invoice_sequence?: int, phone?: string, preferred_locales?: string[], shipping?: null|array{address: array{city?: string, country?: string, line1?: string, line2?: string, postal_code?: string, state?: string}, name: string, phone?: string}, source?: string, tax?: array{ip_address?: null|string, validate_location?: string}, tax_exempt?: null|string, validate?: bool} $params
 * @param null|array|string $opts
 *
 * @return Customer the updated resource
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 155,
        'endLine' => 165,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Customer',
        'implementingClassName' => 'Stripe\\Customer',
        'currentClassName' => 'Stripe\\Customer',
        'aliasName' => NULL,
      ),
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
        'docComment' => NULL,
        'startLine' => 167,
        'endLine' => 177,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Customer',
        'implementingClassName' => 'Stripe\\Customer',
        'currentClassName' => 'Stripe\\Customer',
        'aliasName' => NULL,
      ),
      'deleteDiscount' => 
      array (
        'name' => 'deleteDiscount',
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
                'startLine' => 185,
                'endLine' => 185,
                'startTokenPos' => 561,
                'startFilePos' => 13634,
                'endTokenPos' => 561,
                'endFilePos' => 13637,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 185,
            'endLine' => 185,
            'startColumn' => 36,
            'endColumn' => 49,
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
                'startLine' => 185,
                'endLine' => 185,
                'startTokenPos' => 568,
                'startFilePos' => 13648,
                'endTokenPos' => 568,
                'endFilePos' => 13651,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 185,
            'endLine' => 185,
            'startColumn' => 52,
            'endColumn' => 63,
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
 * @return Customer the updated customer
 */',
        'startLine' => 185,
        'endLine' => 192,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Customer',
        'implementingClassName' => 'Stripe\\Customer',
        'currentClassName' => 'Stripe\\Customer',
        'aliasName' => NULL,
      ),
      'allPaymentMethods' => 
      array (
        'name' => 'allPaymentMethods',
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
            'startLine' => 203,
            'endLine' => 203,
            'startColumn' => 46,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 203,
                'endLine' => 203,
                'startTokenPos' => 659,
                'startFilePos' => 14201,
                'endTokenPos' => 659,
                'endFilePos' => 14204,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 203,
            'endLine' => 203,
            'startColumn' => 51,
            'endColumn' => 64,
            'parameterIndex' => 1,
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
                'startLine' => 203,
                'endLine' => 203,
                'startTokenPos' => 666,
                'startFilePos' => 14215,
                'endTokenPos' => 666,
                'endFilePos' => 14218,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 203,
            'endLine' => 203,
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
 * @param string $id
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return Collection<PaymentMethod> list of payment methods
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 203,
        'endLine' => 211,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Customer',
        'implementingClassName' => 'Stripe\\Customer',
        'currentClassName' => 'Stripe\\Customer',
        'aliasName' => NULL,
      ),
      'retrievePaymentMethod' => 
      array (
        'name' => 'retrievePaymentMethod',
        'parameters' => 
        array (
          'payment_method' => 
          array (
            'name' => 'payment_method',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 222,
            'endLine' => 222,
            'startColumn' => 43,
            'endColumn' => 57,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 222,
                'endLine' => 222,
                'startTokenPos' => 761,
                'startFilePos' => 14848,
                'endTokenPos' => 761,
                'endFilePos' => 14851,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 222,
            'endLine' => 222,
            'startColumn' => 60,
            'endColumn' => 73,
            'parameterIndex' => 1,
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
                'startLine' => 222,
                'endLine' => 222,
                'startTokenPos' => 768,
                'startFilePos' => 14862,
                'endTokenPos' => 768,
                'endFilePos' => 14865,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 222,
            'endLine' => 222,
            'startColumn' => 76,
            'endColumn' => 87,
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
 * @param string $payment_method
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return PaymentMethod the retrieved payment method
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 222,
        'endLine' => 230,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Customer',
        'implementingClassName' => 'Stripe\\Customer',
        'currentClassName' => 'Stripe\\Customer',
        'aliasName' => NULL,
      ),
      'search' => 
      array (
        'name' => 'search',
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
                'startLine' => 240,
                'endLine' => 240,
                'startTokenPos' => 863,
                'startFilePos' => 15443,
                'endTokenPos' => 863,
                'endFilePos' => 15446,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 240,
            'endLine' => 240,
            'startColumn' => 35,
            'endColumn' => 48,
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
                'startLine' => 240,
                'endLine' => 240,
                'startTokenPos' => 870,
                'startFilePos' => 15457,
                'endTokenPos' => 870,
                'endFilePos' => 15460,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 240,
            'endLine' => 240,
            'startColumn' => 51,
            'endColumn' => 62,
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
 * @return SearchResult<Customer> the customer search results
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 240,
        'endLine' => 245,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Customer',
        'implementingClassName' => 'Stripe\\Customer',
        'currentClassName' => 'Stripe\\Customer',
        'aliasName' => NULL,
      ),
      'retrieveCashBalance' => 
      array (
        'name' => 'retrieveCashBalance',
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
            'startLine' => 259,
            'endLine' => 259,
            'startColumn' => 48,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'cashBalanceId' => 
          array (
            'name' => 'cashBalanceId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 259,
            'endLine' => 259,
            'startColumn' => 53,
            'endColumn' => 66,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 259,
                'endLine' => 259,
                'startTokenPos' => 934,
                'startFilePos' => 16031,
                'endTokenPos' => 934,
                'endFilePos' => 16034,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 259,
            'endLine' => 259,
            'startColumn' => 69,
            'endColumn' => 82,
            'parameterIndex' => 2,
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
                'startLine' => 259,
                'endLine' => 259,
                'startTokenPos' => 941,
                'startFilePos' => 16045,
                'endTokenPos' => 941,
                'endFilePos' => 16048,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 259,
            'endLine' => 259,
            'startColumn' => 85,
            'endColumn' => 96,
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
 * @param string $id the ID of the customer to which the cash balance belongs
 * @param null|array $params
 * @param null|array|string $opts
 * @param mixed $cashBalanceId
 *
 * @return CashBalance
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 259,
        'endLine' => 262,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Customer',
        'implementingClassName' => 'Stripe\\Customer',
        'currentClassName' => 'Stripe\\Customer',
        'aliasName' => NULL,
      ),
      'updateCashBalance' => 
      array (
        'name' => 'updateCashBalance',
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
            'startLine' => 274,
            'endLine' => 274,
            'startColumn' => 46,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'cashBalanceId' => 
          array (
            'name' => 'cashBalanceId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 274,
            'endLine' => 274,
            'startColumn' => 51,
            'endColumn' => 64,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 274,
                'endLine' => 274,
                'startTokenPos' => 989,
                'startFilePos' => 16543,
                'endTokenPos' => 989,
                'endFilePos' => 16546,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 274,
            'endLine' => 274,
            'startColumn' => 67,
            'endColumn' => 80,
            'parameterIndex' => 2,
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
                'startLine' => 274,
                'endLine' => 274,
                'startTokenPos' => 996,
                'startFilePos' => 16557,
                'endTokenPos' => 996,
                'endFilePos' => 16560,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 274,
            'endLine' => 274,
            'startColumn' => 83,
            'endColumn' => 94,
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
 * @param string $id the ID of the customer to which the cash balance belongs
 * @param null|array $params
 * @param null|array|string $opts
 * @param mixed $cashBalanceId
 *
 * @return CashBalance
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 274,
        'endLine' => 277,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Customer',
        'implementingClassName' => 'Stripe\\Customer',
        'currentClassName' => 'Stripe\\Customer',
        'aliasName' => NULL,
      ),
      'allBalanceTransactions' => 
      array (
        'name' => 'allBalanceTransactions',
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
            'startLine' => 289,
            'endLine' => 289,
            'startColumn' => 51,
            'endColumn' => 53,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 289,
                'endLine' => 289,
                'startTokenPos' => 1050,
                'startFilePos' => 17160,
                'endTokenPos' => 1050,
                'endFilePos' => 17163,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 289,
            'endLine' => 289,
            'startColumn' => 56,
            'endColumn' => 69,
            'parameterIndex' => 1,
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
                'startLine' => 289,
                'endLine' => 289,
                'startTokenPos' => 1057,
                'startFilePos' => 17174,
                'endTokenPos' => 1057,
                'endFilePos' => 17177,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 289,
            'endLine' => 289,
            'startColumn' => 72,
            'endColumn' => 83,
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
 * @param string $id the ID of the customer on which to retrieve the customer balance transactions
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return Collection<CustomerBalanceTransaction> the list of customer balance transactions
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 289,
        'endLine' => 292,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Customer',
        'implementingClassName' => 'Stripe\\Customer',
        'currentClassName' => 'Stripe\\Customer',
        'aliasName' => NULL,
      ),
      'createBalanceTransaction' => 
      array (
        'name' => 'createBalanceTransaction',
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
            'startLine' => 303,
            'endLine' => 303,
            'startColumn' => 53,
            'endColumn' => 55,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 303,
                'endLine' => 303,
                'startTokenPos' => 1102,
                'startFilePos' => 17665,
                'endTokenPos' => 1102,
                'endFilePos' => 17668,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 303,
            'endLine' => 303,
            'startColumn' => 58,
            'endColumn' => 71,
            'parameterIndex' => 1,
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
                'startLine' => 303,
                'endLine' => 303,
                'startTokenPos' => 1109,
                'startFilePos' => 17679,
                'endTokenPos' => 1109,
                'endFilePos' => 17682,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 303,
            'endLine' => 303,
            'startColumn' => 74,
            'endColumn' => 85,
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
 * @param string $id the ID of the customer on which to create the customer balance transaction
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return CustomerBalanceTransaction
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 303,
        'endLine' => 306,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Customer',
        'implementingClassName' => 'Stripe\\Customer',
        'currentClassName' => 'Stripe\\Customer',
        'aliasName' => NULL,
      ),
      'retrieveBalanceTransaction' => 
      array (
        'name' => 'retrieveBalanceTransaction',
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
            'startLine' => 318,
            'endLine' => 318,
            'startColumn' => 55,
            'endColumn' => 57,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'balanceTransactionId' => 
          array (
            'name' => 'balanceTransactionId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 318,
            'endLine' => 318,
            'startColumn' => 60,
            'endColumn' => 80,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 318,
                'endLine' => 318,
                'startTokenPos' => 1157,
                'startFilePos' => 18293,
                'endTokenPos' => 1157,
                'endFilePos' => 18296,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 318,
            'endLine' => 318,
            'startColumn' => 83,
            'endColumn' => 96,
            'parameterIndex' => 2,
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
                'startLine' => 318,
                'endLine' => 318,
                'startTokenPos' => 1164,
                'startFilePos' => 18307,
                'endTokenPos' => 1164,
                'endFilePos' => 18310,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 318,
            'endLine' => 318,
            'startColumn' => 99,
            'endColumn' => 110,
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
 * @param string $id the ID of the customer to which the customer balance transaction belongs
 * @param string $balanceTransactionId the ID of the customer balance transaction to retrieve
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return CustomerBalanceTransaction
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 318,
        'endLine' => 321,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Customer',
        'implementingClassName' => 'Stripe\\Customer',
        'currentClassName' => 'Stripe\\Customer',
        'aliasName' => NULL,
      ),
      'updateBalanceTransaction' => 
      array (
        'name' => 'updateBalanceTransaction',
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
            'startLine' => 333,
            'endLine' => 333,
            'startColumn' => 53,
            'endColumn' => 55,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'balanceTransactionId' => 
          array (
            'name' => 'balanceTransactionId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 333,
            'endLine' => 333,
            'startColumn' => 58,
            'endColumn' => 78,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 333,
                'endLine' => 333,
                'startTokenPos' => 1215,
                'startFilePos' => 18942,
                'endTokenPos' => 1215,
                'endFilePos' => 18945,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 333,
            'endLine' => 333,
            'startColumn' => 81,
            'endColumn' => 94,
            'parameterIndex' => 2,
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
                'startLine' => 333,
                'endLine' => 333,
                'startTokenPos' => 1222,
                'startFilePos' => 18956,
                'endTokenPos' => 1222,
                'endFilePos' => 18959,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 333,
            'endLine' => 333,
            'startColumn' => 97,
            'endColumn' => 108,
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
 * @param string $id the ID of the customer to which the customer balance transaction belongs
 * @param string $balanceTransactionId the ID of the customer balance transaction to update
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return CustomerBalanceTransaction
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 333,
        'endLine' => 336,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Customer',
        'implementingClassName' => 'Stripe\\Customer',
        'currentClassName' => 'Stripe\\Customer',
        'aliasName' => NULL,
      ),
      'allCashBalanceTransactions' => 
      array (
        'name' => 'allCashBalanceTransactions',
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
            'startLine' => 348,
            'endLine' => 348,
            'startColumn' => 55,
            'endColumn' => 57,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 348,
                'endLine' => 348,
                'startTokenPos' => 1279,
                'startFilePos' => 19618,
                'endTokenPos' => 1279,
                'endFilePos' => 19621,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 348,
            'endLine' => 348,
            'startColumn' => 60,
            'endColumn' => 73,
            'parameterIndex' => 1,
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
                'startLine' => 348,
                'endLine' => 348,
                'startTokenPos' => 1286,
                'startFilePos' => 19632,
                'endTokenPos' => 1286,
                'endFilePos' => 19635,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 348,
            'endLine' => 348,
            'startColumn' => 76,
            'endColumn' => 87,
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
 * @param string $id the ID of the customer on which to retrieve the customer cash balance transactions
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return Collection<CustomerCashBalanceTransaction> the list of customer cash balance transactions
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 348,
        'endLine' => 351,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Customer',
        'implementingClassName' => 'Stripe\\Customer',
        'currentClassName' => 'Stripe\\Customer',
        'aliasName' => NULL,
      ),
      'retrieveCashBalanceTransaction' => 
      array (
        'name' => 'retrieveCashBalanceTransaction',
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
            'startLine' => 363,
            'endLine' => 363,
            'startColumn' => 59,
            'endColumn' => 61,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'cashBalanceTransactionId' => 
          array (
            'name' => 'cashBalanceTransactionId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 363,
            'endLine' => 363,
            'startColumn' => 64,
            'endColumn' => 88,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 363,
                'endLine' => 363,
                'startTokenPos' => 1334,
                'startFilePos' => 20275,
                'endTokenPos' => 1334,
                'endFilePos' => 20278,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 363,
            'endLine' => 363,
            'startColumn' => 91,
            'endColumn' => 104,
            'parameterIndex' => 2,
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
                'startLine' => 363,
                'endLine' => 363,
                'startTokenPos' => 1341,
                'startFilePos' => 20289,
                'endTokenPos' => 1341,
                'endFilePos' => 20292,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 363,
            'endLine' => 363,
            'startColumn' => 107,
            'endColumn' => 118,
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
 * @param string $id the ID of the customer to which the customer cash balance transaction belongs
 * @param string $cashBalanceTransactionId the ID of the customer cash balance transaction to retrieve
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return CustomerCashBalanceTransaction
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 363,
        'endLine' => 366,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Customer',
        'implementingClassName' => 'Stripe\\Customer',
        'currentClassName' => 'Stripe\\Customer',
        'aliasName' => NULL,
      ),
      'allSources' => 
      array (
        'name' => 'allSources',
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
            'startLine' => 378,
            'endLine' => 378,
            'startColumn' => 39,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 378,
                'endLine' => 378,
                'startTokenPos' => 1398,
                'startFilePos' => 20895,
                'endTokenPos' => 1398,
                'endFilePos' => 20898,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 378,
            'endLine' => 378,
            'startColumn' => 44,
            'endColumn' => 57,
            'parameterIndex' => 1,
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
                'startLine' => 378,
                'endLine' => 378,
                'startTokenPos' => 1405,
                'startFilePos' => 20909,
                'endTokenPos' => 1405,
                'endFilePos' => 20912,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 378,
            'endLine' => 378,
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
 * @param string $id the ID of the customer on which to retrieve the payment sources
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return Collection<BankAccount|Card|Source> the list of payment sources (BankAccount, Card or Source)
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 378,
        'endLine' => 381,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Customer',
        'implementingClassName' => 'Stripe\\Customer',
        'currentClassName' => 'Stripe\\Customer',
        'aliasName' => NULL,
      ),
      'createSource' => 
      array (
        'name' => 'createSource',
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
            'startLine' => 392,
            'endLine' => 392,
            'startColumn' => 41,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 392,
                'endLine' => 392,
                'startTokenPos' => 1450,
                'startFilePos' => 21358,
                'endTokenPos' => 1450,
                'endFilePos' => 21361,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 392,
            'endLine' => 392,
            'startColumn' => 46,
            'endColumn' => 59,
            'parameterIndex' => 1,
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
                'startLine' => 392,
                'endLine' => 392,
                'startTokenPos' => 1457,
                'startFilePos' => 21372,
                'endTokenPos' => 1457,
                'endFilePos' => 21375,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 392,
            'endLine' => 392,
            'startColumn' => 62,
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
 * @param string $id the ID of the customer on which to create the payment source
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return BankAccount|Card|Source
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 392,
        'endLine' => 395,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Customer',
        'implementingClassName' => 'Stripe\\Customer',
        'currentClassName' => 'Stripe\\Customer',
        'aliasName' => NULL,
      ),
      'deleteSource' => 
      array (
        'name' => 'deleteSource',
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
            'startLine' => 407,
            'endLine' => 407,
            'startColumn' => 41,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'sourceId' => 
          array (
            'name' => 'sourceId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 407,
            'endLine' => 407,
            'startColumn' => 46,
            'endColumn' => 54,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 407,
                'endLine' => 407,
                'startTokenPos' => 1505,
                'startFilePos' => 21902,
                'endTokenPos' => 1505,
                'endFilePos' => 21905,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 407,
            'endLine' => 407,
            'startColumn' => 57,
            'endColumn' => 70,
            'parameterIndex' => 2,
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
                'startLine' => 407,
                'endLine' => 407,
                'startTokenPos' => 1512,
                'startFilePos' => 21916,
                'endTokenPos' => 1512,
                'endFilePos' => 21919,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 407,
            'endLine' => 407,
            'startColumn' => 73,
            'endColumn' => 84,
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
 * @param string $id the ID of the customer to which the payment source belongs
 * @param string $sourceId the ID of the payment source to delete
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return BankAccount|Card|Source
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 407,
        'endLine' => 410,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Customer',
        'implementingClassName' => 'Stripe\\Customer',
        'currentClassName' => 'Stripe\\Customer',
        'aliasName' => NULL,
      ),
      'retrieveSource' => 
      array (
        'name' => 'retrieveSource',
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
            'startLine' => 422,
            'endLine' => 422,
            'startColumn' => 43,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'sourceId' => 
          array (
            'name' => 'sourceId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 422,
            'endLine' => 422,
            'startColumn' => 48,
            'endColumn' => 56,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 422,
                'endLine' => 422,
                'startTokenPos' => 1563,
                'startFilePos' => 22461,
                'endTokenPos' => 1563,
                'endFilePos' => 22464,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 422,
            'endLine' => 422,
            'startColumn' => 59,
            'endColumn' => 72,
            'parameterIndex' => 2,
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
                'startLine' => 422,
                'endLine' => 422,
                'startTokenPos' => 1570,
                'startFilePos' => 22475,
                'endTokenPos' => 1570,
                'endFilePos' => 22478,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 422,
            'endLine' => 422,
            'startColumn' => 75,
            'endColumn' => 86,
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
 * @param string $id the ID of the customer to which the payment source belongs
 * @param string $sourceId the ID of the payment source to retrieve
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return BankAccount|Card|Source
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 422,
        'endLine' => 425,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Customer',
        'implementingClassName' => 'Stripe\\Customer',
        'currentClassName' => 'Stripe\\Customer',
        'aliasName' => NULL,
      ),
      'updateSource' => 
      array (
        'name' => 'updateSource',
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
            'startLine' => 437,
            'endLine' => 437,
            'startColumn' => 41,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'sourceId' => 
          array (
            'name' => 'sourceId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 437,
            'endLine' => 437,
            'startColumn' => 46,
            'endColumn' => 54,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 437,
                'endLine' => 437,
                'startTokenPos' => 1621,
                'startFilePos' => 23018,
                'endTokenPos' => 1621,
                'endFilePos' => 23021,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 437,
            'endLine' => 437,
            'startColumn' => 57,
            'endColumn' => 70,
            'parameterIndex' => 2,
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
                'startLine' => 437,
                'endLine' => 437,
                'startTokenPos' => 1628,
                'startFilePos' => 23032,
                'endTokenPos' => 1628,
                'endFilePos' => 23035,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 437,
            'endLine' => 437,
            'startColumn' => 73,
            'endColumn' => 84,
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
 * @param string $id the ID of the customer to which the payment source belongs
 * @param string $sourceId the ID of the payment source to update
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return BankAccount|Card|Source
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 437,
        'endLine' => 440,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Customer',
        'implementingClassName' => 'Stripe\\Customer',
        'currentClassName' => 'Stripe\\Customer',
        'aliasName' => NULL,
      ),
      'allTaxIds' => 
      array (
        'name' => 'allTaxIds',
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
            'startLine' => 452,
            'endLine' => 452,
            'startColumn' => 38,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 452,
                'endLine' => 452,
                'startTokenPos' => 1685,
                'startFilePos' => 23537,
                'endTokenPos' => 1685,
                'endFilePos' => 23540,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 452,
            'endLine' => 452,
            'startColumn' => 43,
            'endColumn' => 56,
            'parameterIndex' => 1,
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
                'startLine' => 452,
                'endLine' => 452,
                'startTokenPos' => 1692,
                'startFilePos' => 23551,
                'endTokenPos' => 1692,
                'endFilePos' => 23554,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 452,
            'endLine' => 452,
            'startColumn' => 59,
            'endColumn' => 70,
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
 * @param string $id the ID of the customer on which to retrieve the tax ids
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return Collection<TaxId> the list of tax ids
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 452,
        'endLine' => 455,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Customer',
        'implementingClassName' => 'Stripe\\Customer',
        'currentClassName' => 'Stripe\\Customer',
        'aliasName' => NULL,
      ),
      'createTaxId' => 
      array (
        'name' => 'createTaxId',
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
            'startLine' => 466,
            'endLine' => 466,
            'startColumn' => 40,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 466,
                'endLine' => 466,
                'startTokenPos' => 1737,
                'startFilePos' => 23973,
                'endTokenPos' => 1737,
                'endFilePos' => 23976,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 466,
            'endLine' => 466,
            'startColumn' => 45,
            'endColumn' => 58,
            'parameterIndex' => 1,
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
                'startLine' => 466,
                'endLine' => 466,
                'startTokenPos' => 1744,
                'startFilePos' => 23987,
                'endTokenPos' => 1744,
                'endFilePos' => 23990,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 466,
            'endLine' => 466,
            'startColumn' => 61,
            'endColumn' => 72,
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
 * @param string $id the ID of the customer on which to create the tax id
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return TaxId
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 466,
        'endLine' => 469,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Customer',
        'implementingClassName' => 'Stripe\\Customer',
        'currentClassName' => 'Stripe\\Customer',
        'aliasName' => NULL,
      ),
      'deleteTaxId' => 
      array (
        'name' => 'deleteTaxId',
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
            'startLine' => 481,
            'endLine' => 481,
            'startColumn' => 40,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'taxIdId' => 
          array (
            'name' => 'taxIdId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 481,
            'endLine' => 481,
            'startColumn' => 45,
            'endColumn' => 52,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 481,
                'endLine' => 481,
                'startTokenPos' => 1792,
                'startFilePos' => 24480,
                'endTokenPos' => 1792,
                'endFilePos' => 24483,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 481,
            'endLine' => 481,
            'startColumn' => 55,
            'endColumn' => 68,
            'parameterIndex' => 2,
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
                'startLine' => 481,
                'endLine' => 481,
                'startTokenPos' => 1799,
                'startFilePos' => 24494,
                'endTokenPos' => 1799,
                'endFilePos' => 24497,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 481,
            'endLine' => 481,
            'startColumn' => 71,
            'endColumn' => 82,
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
 * @param string $id the ID of the customer to which the tax id belongs
 * @param string $taxIdId the ID of the tax id to delete
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return TaxId
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 481,
        'endLine' => 484,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Customer',
        'implementingClassName' => 'Stripe\\Customer',
        'currentClassName' => 'Stripe\\Customer',
        'aliasName' => NULL,
      ),
      'retrieveTaxId' => 
      array (
        'name' => 'retrieveTaxId',
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
            'startLine' => 496,
            'endLine' => 496,
            'startColumn' => 42,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'taxIdId' => 
          array (
            'name' => 'taxIdId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 496,
            'endLine' => 496,
            'startColumn' => 47,
            'endColumn' => 54,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'params' => 
          array (
            'name' => 'params',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 496,
                'endLine' => 496,
                'startTokenPos' => 1850,
                'startFilePos' => 25001,
                'endTokenPos' => 1850,
                'endFilePos' => 25004,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 496,
            'endLine' => 496,
            'startColumn' => 57,
            'endColumn' => 70,
            'parameterIndex' => 2,
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
                'startLine' => 496,
                'endLine' => 496,
                'startTokenPos' => 1857,
                'startFilePos' => 25015,
                'endTokenPos' => 1857,
                'endFilePos' => 25018,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 496,
            'endLine' => 496,
            'startColumn' => 73,
            'endColumn' => 84,
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
 * @param string $id the ID of the customer to which the tax id belongs
 * @param string $taxIdId the ID of the tax id to retrieve
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return TaxId
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 496,
        'endLine' => 499,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Customer',
        'implementingClassName' => 'Stripe\\Customer',
        'currentClassName' => 'Stripe\\Customer',
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