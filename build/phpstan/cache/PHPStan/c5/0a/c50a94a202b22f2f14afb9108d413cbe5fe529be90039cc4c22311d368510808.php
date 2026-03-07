<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/Card.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Stripe\Card
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-3fd7bda77709d3557a67b7703c843c674f8bc647738f3033da831b66fa983274-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Stripe\\Card',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/Card.php',
      ),
    ),
    'namespace' => 'Stripe',
    'name' => 'Stripe\\Card',
    'shortName' => 'Card',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * You can store multiple cards on a customer in order to charge the customer
 * later. You can also store multiple debit cards on a recipient in order to
 * transfer to those cards later.
 *
 * Related guide: <a href="https://stripe.com/docs/sources/cards">Card payments with Sources</a>
 *
 * @property string $id Unique identifier for the object.
 * @property string $object String representing the object\'s type. Objects of the same type share the same value.
 * @property null|Account|string $account
 * @property null|string $address_city City/District/Suburb/Town/Village.
 * @property null|string $address_country Billing address country, if provided when creating card.
 * @property null|string $address_line1 Address line 1 (Street address/PO Box/Company name).
 * @property null|string $address_line1_check If <code>address_line1</code> was provided, results of the check: <code>pass</code>, <code>fail</code>, <code>unavailable</code>, or <code>unchecked</code>.
 * @property null|string $address_line2 Address line 2 (Apartment/Suite/Unit/Building).
 * @property null|string $address_state State/County/Province/Region.
 * @property null|string $address_zip ZIP or postal code.
 * @property null|string $address_zip_check If <code>address_zip</code> was provided, results of the check: <code>pass</code>, <code>fail</code>, <code>unavailable</code>, or <code>unchecked</code>.
 * @property null|string $allow_redisplay This field indicates whether this payment method can be shown again to its customer in a checkout flow. Stripe products such as Checkout and Elements use this field to determine whether a payment method can be shown as a saved payment method in a checkout flow. The field defaults to “unspecified”.
 * @property null|string[] $available_payout_methods A set of available payout methods for this card. Only values from this set should be passed as the <code>method</code> when creating a payout.
 * @property string $brand Card brand. Can be <code>American Express</code>, <code>Diners Club</code>, <code>Discover</code>, <code>Eftpos Australia</code>, <code>Girocard</code>, <code>JCB</code>, <code>MasterCard</code>, <code>UnionPay</code>, <code>Visa</code>, or <code>Unknown</code>.
 * @property null|string $country Two-letter ISO code representing the country of the card. You could use this attribute to get a sense of the international breakdown of cards you\'ve collected.
 * @property null|string $currency Three-letter <a href="https://www.iso.org/iso-4217-currency-codes.html">ISO code for currency</a> in lowercase. Must be a <a href="https://docs.stripe.com/currencies">supported currency</a>. Only applicable on accounts (not customers or recipients). The card can be used as a transfer destination for funds in this currency. This property is only available when returned as an <a href="/api/external_account_cards/object">External Account</a> where <a href="/api/accounts/object#account_object-controller-is_controller">controller.is_controller</a> is <code>true</code>.
 * @property null|Customer|string $customer The customer that this card belongs to. This attribute will not be in the card object if the card belongs to an account or recipient instead.
 * @property null|string $cvc_check If a CVC was provided, results of the check: <code>pass</code>, <code>fail</code>, <code>unavailable</code>, or <code>unchecked</code>. A result of unchecked indicates that CVC was provided but hasn\'t been checked yet. Checks are typically performed when attaching a card to a Customer object, or when creating a charge. For more details, see <a href="https://support.stripe.com/questions/check-if-a-card-is-valid-without-a-charge">Check if a card is valid without a charge</a>.
 * @property null|bool $default_for_currency Whether this card is the default external account for its currency. This property is only available for accounts where <a href="/api/accounts/object#account_object-controller-requirement_collection">controller.requirement_collection</a> is <code>application</code>, which includes Custom accounts.
 * @property null|string $dynamic_last4 (For tokenized numbers only.) The last four digits of the device account number.
 * @property int $exp_month Two-digit number representing the card\'s expiration month.
 * @property int $exp_year Four-digit number representing the card\'s expiration year.
 * @property null|string $fingerprint <p>Uniquely identifies this particular card number. You can use this attribute to check whether two customers who’ve signed up with you are using the same card number, for example. For payment methods that tokenize card information (Apple Pay, Google Pay), the tokenized number might be provided instead of the underlying card number.</p><p><em>As of May 1, 2021, card fingerprint in India for Connect changed to allow two fingerprints for the same card---one for India and one for the rest of the world.</em></p>
 * @property string $funding Card funding type. Can be <code>credit</code>, <code>debit</code>, <code>prepaid</code>, or <code>unknown</code>.
 * @property string $last4 The last four digits of the card.
 * @property null|StripeObject $metadata Set of <a href="https://stripe.com/docs/api/metadata">key-value pairs</a> that you can attach to an object. This can be useful for storing additional information about the object in a structured format.
 * @property null|string $name Cardholder name.
 * @property null|(object{preferred: null|string}&StripeObject) $networks
 * @property null|string $regulated_status Status of a card based on the card issuer.
 * @property null|string $status For external accounts that are cards, possible values are <code>new</code> and <code>errored</code>. If a payout fails, the status is set to <code>errored</code> and <a href="https://stripe.com/docs/payouts#payout-schedule">scheduled payouts</a> are stopped until account details are updated.
 * @property null|string $tokenization_method If the card number is tokenized, this is the method that was used. Can be <code>android_pay</code> (includes Google Pay), <code>apple_pay</code>, <code>masterpass</code>, <code>visa_checkout</code>, or null.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 46,
    'endLine' => 188,
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
        'declaringClassName' => 'Stripe\\Card',
        'implementingClassName' => 'Stripe\\Card',
        'name' => 'OBJECT_NAME',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'card\'',
          'attributes' => 
          array (
            'startLine' => 48,
            'endLine' => 48,
            'startTokenPos' => 27,
            'startFilePos' => 6281,
            'endTokenPos' => 27,
            'endFilePos' => 6286,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 48,
        'endLine' => 48,
        'startColumn' => 5,
        'endColumn' => 31,
      ),
      'ALLOW_REDISPLAY_ALWAYS' => 
      array (
        'declaringClassName' => 'Stripe\\Card',
        'implementingClassName' => 'Stripe\\Card',
        'name' => 'ALLOW_REDISPLAY_ALWAYS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'always\'',
          'attributes' => 
          array (
            'startLine' => 50,
            'endLine' => 50,
            'startTokenPos' => 36,
            'startFilePos' => 6325,
            'endTokenPos' => 36,
            'endFilePos' => 6332,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 50,
        'endLine' => 50,
        'startColumn' => 5,
        'endColumn' => 44,
      ),
      'ALLOW_REDISPLAY_LIMITED' => 
      array (
        'declaringClassName' => 'Stripe\\Card',
        'implementingClassName' => 'Stripe\\Card',
        'name' => 'ALLOW_REDISPLAY_LIMITED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'limited\'',
          'attributes' => 
          array (
            'startLine' => 51,
            'endLine' => 51,
            'startTokenPos' => 45,
            'startFilePos' => 6371,
            'endTokenPos' => 45,
            'endFilePos' => 6379,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 51,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 46,
      ),
      'ALLOW_REDISPLAY_UNSPECIFIED' => 
      array (
        'declaringClassName' => 'Stripe\\Card',
        'implementingClassName' => 'Stripe\\Card',
        'name' => 'ALLOW_REDISPLAY_UNSPECIFIED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'unspecified\'',
          'attributes' => 
          array (
            'startLine' => 52,
            'endLine' => 52,
            'startTokenPos' => 54,
            'startFilePos' => 6422,
            'endTokenPos' => 54,
            'endFilePos' => 6434,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 52,
        'endLine' => 52,
        'startColumn' => 5,
        'endColumn' => 54,
      ),
      'REGULATED_STATUS_REGULATED' => 
      array (
        'declaringClassName' => 'Stripe\\Card',
        'implementingClassName' => 'Stripe\\Card',
        'name' => 'REGULATED_STATUS_REGULATED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'regulated\'',
          'attributes' => 
          array (
            'startLine' => 54,
            'endLine' => 54,
            'startTokenPos' => 63,
            'startFilePos' => 6477,
            'endTokenPos' => 63,
            'endFilePos' => 6487,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 54,
        'endLine' => 54,
        'startColumn' => 5,
        'endColumn' => 51,
      ),
      'REGULATED_STATUS_UNREGULATED' => 
      array (
        'declaringClassName' => 'Stripe\\Card',
        'implementingClassName' => 'Stripe\\Card',
        'name' => 'REGULATED_STATUS_UNREGULATED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'unregulated\'',
          'attributes' => 
          array (
            'startLine' => 55,
            'endLine' => 55,
            'startTokenPos' => 72,
            'startFilePos' => 6531,
            'endTokenPos' => 72,
            'endFilePos' => 6543,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 55,
        'endLine' => 55,
        'startColumn' => 5,
        'endColumn' => 55,
      ),
      'CVC_CHECK_FAIL' => 
      array (
        'declaringClassName' => 'Stripe\\Card',
        'implementingClassName' => 'Stripe\\Card',
        'name' => 'CVC_CHECK_FAIL',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'fail\'',
          'attributes' => 
          array (
            'startLine' => 83,
            'endLine' => 83,
            'startTokenPos' => 171,
            'startFilePos' => 7312,
            'endTokenPos' => 171,
            'endFilePos' => 7317,
          ),
        ),
        'docComment' => '/**
 * Possible string representations of the CVC check status.
 *
 * @see https://stripe.com/docs/api/cards/object#card_object-cvc_check
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 83,
        'endLine' => 83,
        'startColumn' => 5,
        'endColumn' => 34,
      ),
      'CVC_CHECK_PASS' => 
      array (
        'declaringClassName' => 'Stripe\\Card',
        'implementingClassName' => 'Stripe\\Card',
        'name' => 'CVC_CHECK_PASS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'pass\'',
          'attributes' => 
          array (
            'startLine' => 84,
            'endLine' => 84,
            'startTokenPos' => 180,
            'startFilePos' => 7347,
            'endTokenPos' => 180,
            'endFilePos' => 7352,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 84,
        'endLine' => 84,
        'startColumn' => 5,
        'endColumn' => 34,
      ),
      'CVC_CHECK_UNAVAILABLE' => 
      array (
        'declaringClassName' => 'Stripe\\Card',
        'implementingClassName' => 'Stripe\\Card',
        'name' => 'CVC_CHECK_UNAVAILABLE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'unavailable\'',
          'attributes' => 
          array (
            'startLine' => 85,
            'endLine' => 85,
            'startTokenPos' => 189,
            'startFilePos' => 7389,
            'endTokenPos' => 189,
            'endFilePos' => 7401,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 85,
        'endLine' => 85,
        'startColumn' => 5,
        'endColumn' => 48,
      ),
      'CVC_CHECK_UNCHECKED' => 
      array (
        'declaringClassName' => 'Stripe\\Card',
        'implementingClassName' => 'Stripe\\Card',
        'name' => 'CVC_CHECK_UNCHECKED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'unchecked\'',
          'attributes' => 
          array (
            'startLine' => 86,
            'endLine' => 86,
            'startTokenPos' => 198,
            'startFilePos' => 7436,
            'endTokenPos' => 198,
            'endFilePos' => 7446,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 86,
        'endLine' => 86,
        'startColumn' => 5,
        'endColumn' => 44,
      ),
      'FUNDING_CREDIT' => 
      array (
        'declaringClassName' => 'Stripe\\Card',
        'implementingClassName' => 'Stripe\\Card',
        'name' => 'FUNDING_CREDIT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'credit\'',
          'attributes' => 
          array (
            'startLine' => 93,
            'endLine' => 93,
            'startTokenPos' => 209,
            'startFilePos' => 7640,
            'endTokenPos' => 209,
            'endFilePos' => 7647,
          ),
        ),
        'docComment' => '/**
 * Possible string representations of the funding of the card.
 *
 * @see https://stripe.com/docs/api/cards/object#card_object-funding
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 93,
        'endLine' => 93,
        'startColumn' => 5,
        'endColumn' => 36,
      ),
      'FUNDING_DEBIT' => 
      array (
        'declaringClassName' => 'Stripe\\Card',
        'implementingClassName' => 'Stripe\\Card',
        'name' => 'FUNDING_DEBIT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'debit\'',
          'attributes' => 
          array (
            'startLine' => 94,
            'endLine' => 94,
            'startTokenPos' => 218,
            'startFilePos' => 7676,
            'endTokenPos' => 218,
            'endFilePos' => 7682,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 94,
        'endLine' => 94,
        'startColumn' => 5,
        'endColumn' => 34,
      ),
      'FUNDING_PREPAID' => 
      array (
        'declaringClassName' => 'Stripe\\Card',
        'implementingClassName' => 'Stripe\\Card',
        'name' => 'FUNDING_PREPAID',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'prepaid\'',
          'attributes' => 
          array (
            'startLine' => 95,
            'endLine' => 95,
            'startTokenPos' => 227,
            'startFilePos' => 7713,
            'endTokenPos' => 227,
            'endFilePos' => 7721,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 95,
        'endLine' => 95,
        'startColumn' => 5,
        'endColumn' => 38,
      ),
      'FUNDING_UNKNOWN' => 
      array (
        'declaringClassName' => 'Stripe\\Card',
        'implementingClassName' => 'Stripe\\Card',
        'name' => 'FUNDING_UNKNOWN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'unknown\'',
          'attributes' => 
          array (
            'startLine' => 96,
            'endLine' => 96,
            'startTokenPos' => 236,
            'startFilePos' => 7752,
            'endTokenPos' => 236,
            'endFilePos' => 7760,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 96,
        'endLine' => 96,
        'startColumn' => 5,
        'endColumn' => 38,
      ),
      'TOKENIZATION_METHOD_APPLE_PAY' => 
      array (
        'declaringClassName' => 'Stripe\\Card',
        'implementingClassName' => 'Stripe\\Card',
        'name' => 'TOKENIZATION_METHOD_APPLE_PAY',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'apple_pay\'',
          'attributes' => 
          array (
            'startLine' => 103,
            'endLine' => 103,
            'startTokenPos' => 247,
            'startFilePos' => 8016,
            'endTokenPos' => 247,
            'endFilePos' => 8026,
          ),
        ),
        'docComment' => '/**
 * Possible string representations of the tokenization method when using Apple Pay or Google Pay.
 *
 * @see https://stripe.com/docs/api/cards/object#card_object-tokenization_method
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 103,
        'endLine' => 103,
        'startColumn' => 5,
        'endColumn' => 54,
      ),
      'TOKENIZATION_METHOD_GOOGLE_PAY' => 
      array (
        'declaringClassName' => 'Stripe\\Card',
        'implementingClassName' => 'Stripe\\Card',
        'name' => 'TOKENIZATION_METHOD_GOOGLE_PAY',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'google_pay\'',
          'attributes' => 
          array (
            'startLine' => 104,
            'endLine' => 104,
            'startTokenPos' => 256,
            'startFilePos' => 8072,
            'endTokenPos' => 256,
            'endFilePos' => 8083,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 104,
        'endLine' => 104,
        'startColumn' => 5,
        'endColumn' => 56,
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
                'startLine' => 67,
                'endLine' => 67,
                'startTokenPos' => 87,
                'startFilePos' => 6861,
                'endTokenPos' => 87,
                'endFilePos' => 6864,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 67,
            'endLine' => 67,
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
                'startLine' => 67,
                'endLine' => 67,
                'startTokenPos' => 94,
                'startFilePos' => 6875,
                'endTokenPos' => 94,
                'endFilePos' => 6878,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 67,
            'endLine' => 67,
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
 * @return Card the deleted resource
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 67,
        'endLine' => 76,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Card',
        'implementingClassName' => 'Stripe\\Card',
        'currentClassName' => 'Stripe\\Card',
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
 *    cased because cards are nested resources that may belong to different
 *    top-level resources.
 */',
        'startLine' => 111,
        'endLine' => 130,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Card',
        'implementingClassName' => 'Stripe\\Card',
        'currentClassName' => 'Stripe\\Card',
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
            'startLine' => 138,
            'endLine' => 138,
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
                'startLine' => 138,
                'endLine' => 138,
                'startTokenPos' => 449,
                'startFilePos' => 9194,
                'endTokenPos' => 449,
                'endFilePos' => 9197,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 138,
            'endLine' => 138,
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
        'startLine' => 138,
        'endLine' => 146,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Card',
        'implementingClassName' => 'Stripe\\Card',
        'currentClassName' => 'Stripe\\Card',
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
            'startLine' => 155,
            'endLine' => 155,
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
                'startLine' => 155,
                'endLine' => 155,
                'startTokenPos' => 502,
                'startFilePos' => 9779,
                'endTokenPos' => 502,
                'endFilePos' => 9782,
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
                'startLine' => 155,
                'endLine' => 155,
                'startTokenPos' => 509,
                'startFilePos' => 9797,
                'endTokenPos' => 509,
                'endFilePos' => 9800,
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
        'startLine' => 155,
        'endLine' => 164,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Card',
        'implementingClassName' => 'Stripe\\Card',
        'currentClassName' => 'Stripe\\Card',
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
                'startLine' => 177,
                'endLine' => 177,
                'startTokenPos' => 561,
                'startFilePos' => 10605,
                'endTokenPos' => 561,
                'endFilePos' => 10608,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 177,
            'endLine' => 177,
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
        'startLine' => 177,
        'endLine' => 187,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Card',
        'implementingClassName' => 'Stripe\\Card',
        'currentClassName' => 'Stripe\\Card',
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