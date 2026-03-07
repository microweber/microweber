<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/Mandate.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Stripe\Mandate
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-37f0e09f7f05e38ead9f1bc6eeba045126bcdb098d9f7cb6e7ae6e589c67941b-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Stripe\\Mandate',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/Mandate.php',
      ),
    ),
    'namespace' => 'Stripe',
    'name' => 'Stripe\\Mandate',
    'shortName' => 'Mandate',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A Mandate is a record of the permission that your customer gives you to debit their payment method.
 *
 * @property string $id Unique identifier for the object.
 * @property string $object String representing the object\'s type. Objects of the same type share the same value.
 * @property (object{accepted_at: null|int, offline?: (object{}&StripeObject), online?: (object{ip_address: null|string, user_agent: null|string}&StripeObject), type: string}&StripeObject) $customer_acceptance
 * @property bool $livemode Has the value <code>true</code> if the object exists in live mode or the value <code>false</code> if the object exists in test mode.
 * @property null|(object{}&StripeObject) $multi_use
 * @property null|string $on_behalf_of The account (if any) that the mandate is intended for.
 * @property PaymentMethod|string $payment_method ID of the payment method associated with this mandate.
 * @property (object{acss_debit?: (object{default_for?: string[], interval_description: null|string, payment_schedule: string, transaction_type: string}&StripeObject), amazon_pay?: (object{}&StripeObject), au_becs_debit?: (object{url: string}&StripeObject), bacs_debit?: (object{network_status: string, reference: string, revocation_reason: null|string, url: string}&StripeObject), card?: (object{}&StripeObject), cashapp?: (object{}&StripeObject), kakao_pay?: (object{}&StripeObject), klarna?: (object{}&StripeObject), kr_card?: (object{}&StripeObject), link?: (object{}&StripeObject), naver_pay?: (object{}&StripeObject), nz_bank_account?: (object{}&StripeObject), paypal?: (object{billing_agreement_id: null|string, payer_id: null|string}&StripeObject), revolut_pay?: (object{}&StripeObject), sepa_debit?: (object{reference: string, url: string}&StripeObject), type: string, us_bank_account?: (object{collection_method?: string}&StripeObject)}&StripeObject) $payment_method_details
 * @property null|(object{amount: int, currency: string}&StripeObject) $single_use
 * @property string $status The mandate status indicates whether or not you can use it to initiate a payment.
 * @property string $type The type of the mandate.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 22,
    'endLine' => 51,
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
        'declaringClassName' => 'Stripe\\Mandate',
        'implementingClassName' => 'Stripe\\Mandate',
        'name' => 'OBJECT_NAME',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'mandate\'',
          'attributes' => 
          array (
            'startLine' => 24,
            'endLine' => 24,
            'startTokenPos' => 27,
            'startFilePos' => 2265,
            'endTokenPos' => 27,
            'endFilePos' => 2273,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 24,
        'endLine' => 24,
        'startColumn' => 5,
        'endColumn' => 34,
      ),
      'STATUS_ACTIVE' => 
      array (
        'declaringClassName' => 'Stripe\\Mandate',
        'implementingClassName' => 'Stripe\\Mandate',
        'name' => 'STATUS_ACTIVE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'active\'',
          'attributes' => 
          array (
            'startLine' => 26,
            'endLine' => 26,
            'startTokenPos' => 36,
            'startFilePos' => 2303,
            'endTokenPos' => 36,
            'endFilePos' => 2310,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 26,
        'endLine' => 26,
        'startColumn' => 5,
        'endColumn' => 35,
      ),
      'STATUS_INACTIVE' => 
      array (
        'declaringClassName' => 'Stripe\\Mandate',
        'implementingClassName' => 'Stripe\\Mandate',
        'name' => 'STATUS_INACTIVE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'inactive\'',
          'attributes' => 
          array (
            'startLine' => 27,
            'endLine' => 27,
            'startTokenPos' => 45,
            'startFilePos' => 2341,
            'endTokenPos' => 45,
            'endFilePos' => 2350,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 27,
        'endLine' => 27,
        'startColumn' => 5,
        'endColumn' => 39,
      ),
      'STATUS_PENDING' => 
      array (
        'declaringClassName' => 'Stripe\\Mandate',
        'implementingClassName' => 'Stripe\\Mandate',
        'name' => 'STATUS_PENDING',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'pending\'',
          'attributes' => 
          array (
            'startLine' => 28,
            'endLine' => 28,
            'startTokenPos' => 54,
            'startFilePos' => 2380,
            'endTokenPos' => 54,
            'endFilePos' => 2388,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 28,
        'endLine' => 28,
        'startColumn' => 5,
        'endColumn' => 37,
      ),
      'TYPE_MULTI_USE' => 
      array (
        'declaringClassName' => 'Stripe\\Mandate',
        'implementingClassName' => 'Stripe\\Mandate',
        'name' => 'TYPE_MULTI_USE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'multi_use\'',
          'attributes' => 
          array (
            'startLine' => 30,
            'endLine' => 30,
            'startTokenPos' => 63,
            'startFilePos' => 2419,
            'endTokenPos' => 63,
            'endFilePos' => 2429,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 30,
        'endLine' => 30,
        'startColumn' => 5,
        'endColumn' => 39,
      ),
      'TYPE_SINGLE_USE' => 
      array (
        'declaringClassName' => 'Stripe\\Mandate',
        'implementingClassName' => 'Stripe\\Mandate',
        'name' => 'TYPE_SINGLE_USE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'single_use\'',
          'attributes' => 
          array (
            'startLine' => 31,
            'endLine' => 31,
            'startTokenPos' => 72,
            'startFilePos' => 2460,
            'endTokenPos' => 72,
            'endFilePos' => 2471,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 31,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 41,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
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
            'startLine' => 43,
            'endLine' => 43,
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
                'startLine' => 43,
                'endLine' => 43,
                'startTokenPos' => 92,
                'startFilePos' => 2835,
                'endTokenPos' => 92,
                'endFilePos' => 2838,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 43,
            'endLine' => 43,
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
 * Retrieves a Mandate object.
 *
 * @param array|string $id the ID of the API resource to retrieve, or an options array containing an `id` key
 * @param null|array|string $opts
 *
 * @return Mandate
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 43,
        'endLine' => 50,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Mandate',
        'implementingClassName' => 'Stripe\\Mandate',
        'currentClassName' => 'Stripe\\Mandate',
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