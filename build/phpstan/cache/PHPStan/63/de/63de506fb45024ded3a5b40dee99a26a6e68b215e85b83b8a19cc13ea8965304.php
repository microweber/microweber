<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/Service/PaymentIntentService.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Stripe\Service\PaymentIntentService
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-75b386be420d3e9f4aa7586d982150a021f5ac73694bcae9f7f68e0bf288e0a6-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Stripe\\Service\\PaymentIntentService',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/Service/PaymentIntentService.php',
      ),
    ),
    'namespace' => 'Stripe\\Service',
    'name' => 'Stripe\\Service\\PaymentIntentService',
    'shortName' => 'PaymentIntentService',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @phpstan-import-type RequestOptionsArray from \\Stripe\\Util\\RequestOptions
 *
 * @psalm-import-type RequestOptionsArray from \\Stripe\\Util\\RequestOptions
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 12,
    'endLine' => 290,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Stripe\\Service\\AbstractService',
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
                'startLine' => 24,
                'endLine' => 24,
                'startTokenPos' => 33,
                'startFilePos' => 759,
                'endTokenPos' => 33,
                'endFilePos' => 762,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 24,
            'endLine' => 24,
            'startColumn' => 25,
            'endColumn' => 38,
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
                'startLine' => 24,
                'endLine' => 24,
                'startTokenPos' => 40,
                'startFilePos' => 773,
                'endTokenPos' => 40,
                'endFilePos' => 776,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 24,
            'endLine' => 24,
            'startColumn' => 41,
            'endColumn' => 52,
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
 * Returns a list of PaymentIntents.
 *
 * @param null|array{created?: array|int, customer?: string, ending_before?: string, expand?: string[], limit?: int, starting_after?: string} $params
 * @param null|RequestOptionsArray|\\Stripe\\Util\\RequestOptions $opts
 *
 * @return \\Stripe\\Collection<\\Stripe\\PaymentIntent>
 *
 * @throws \\Stripe\\Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 24,
        'endLine' => 27,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe\\Service',
        'declaringClassName' => 'Stripe\\Service\\PaymentIntentService',
        'implementingClassName' => 'Stripe\\Service\\PaymentIntentService',
        'currentClassName' => 'Stripe\\Service\\PaymentIntentService',
        'aliasName' => NULL,
      ),
      'applyCustomerBalance' => 
      array (
        'name' => 'applyCustomerBalance',
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
            'startLine' => 41,
            'endLine' => 41,
            'startColumn' => 42,
            'endColumn' => 44,
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
                'startLine' => 41,
                'endLine' => 41,
                'startTokenPos' => 81,
                'startFilePos' => 1369,
                'endTokenPos' => 81,
                'endFilePos' => 1372,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 41,
            'endLine' => 41,
            'startColumn' => 47,
            'endColumn' => 60,
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
                'startLine' => 41,
                'endLine' => 41,
                'startTokenPos' => 88,
                'startFilePos' => 1383,
                'endTokenPos' => 88,
                'endFilePos' => 1386,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 41,
            'endLine' => 41,
            'startColumn' => 63,
            'endColumn' => 74,
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
 * Manually reconcile the remaining amount for a <code>customer_balance</code>
 * PaymentIntent.
 *
 * @param string $id
 * @param null|array{amount?: int, currency?: string, expand?: string[]} $params
 * @param null|RequestOptionsArray|\\Stripe\\Util\\RequestOptions $opts
 *
 * @return \\Stripe\\PaymentIntent
 *
 * @throws \\Stripe\\Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 41,
        'endLine' => 44,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe\\Service',
        'declaringClassName' => 'Stripe\\Service\\PaymentIntentService',
        'implementingClassName' => 'Stripe\\Service\\PaymentIntentService',
        'currentClassName' => 'Stripe\\Service\\PaymentIntentService',
        'aliasName' => NULL,
      ),
      'cancel' => 
      array (
        'name' => 'cancel',
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
            'startLine' => 69,
            'endLine' => 69,
            'startColumn' => 28,
            'endColumn' => 30,
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
                'startLine' => 69,
                'endLine' => 69,
                'startTokenPos' => 137,
                'startFilePos' => 2713,
                'endTokenPos' => 137,
                'endFilePos' => 2716,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 69,
            'endLine' => 69,
            'startColumn' => 33,
            'endColumn' => 46,
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
                'startLine' => 69,
                'endLine' => 69,
                'startTokenPos' => 144,
                'startFilePos' => 2727,
                'endTokenPos' => 144,
                'endFilePos' => 2730,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 69,
            'endLine' => 69,
            'startColumn' => 49,
            'endColumn' => 60,
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
 * You can cancel a PaymentIntent object when it’s in one of these statuses:
 * <code>requires_payment_method</code>, <code>requires_capture</code>,
 * <code>requires_confirmation</code>, <code>requires_action</code> or, <a
 * href="/docs/payments/intents">in rare cases</a>, <code>processing</code>.
 *
 * After it’s canceled, no additional charges are made by the PaymentIntent and any
 * operations on the PaymentIntent fail with an error. For PaymentIntents with a
 * <code>status</code> of <code>requires_capture</code>, the remaining
 * <code>amount_capturable</code> is automatically refunded.
 *
 * You can’t cancel the PaymentIntent for a Checkout Session. <a
 * href="/docs/api/checkout/sessions/expire">Expire the Checkout Session</a>
 * instead.
 *
 * @param string $id
 * @param null|array{cancellation_reason?: string, expand?: string[]} $params
 * @param null|RequestOptionsArray|\\Stripe\\Util\\RequestOptions $opts
 *
 * @return \\Stripe\\PaymentIntent
 *
 * @throws \\Stripe\\Exception\\ApiErrorException if the request fails
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
        'namespace' => 'Stripe\\Service',
        'declaringClassName' => 'Stripe\\Service\\PaymentIntentService',
        'implementingClassName' => 'Stripe\\Service\\PaymentIntentService',
        'currentClassName' => 'Stripe\\Service\\PaymentIntentService',
        'aliasName' => NULL,
      ),
      'capture' => 
      array (
        'name' => 'capture',
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
            'startLine' => 92,
            'endLine' => 92,
            'startColumn' => 29,
            'endColumn' => 31,
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
                'startLine' => 92,
                'endLine' => 92,
                'startTokenPos' => 193,
                'startFilePos' => 3776,
                'endTokenPos' => 193,
                'endFilePos' => 3779,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 92,
            'endLine' => 92,
            'startColumn' => 34,
            'endColumn' => 47,
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
                'startLine' => 92,
                'endLine' => 92,
                'startTokenPos' => 200,
                'startFilePos' => 3790,
                'endTokenPos' => 200,
                'endFilePos' => 3793,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 92,
            'endLine' => 92,
            'startColumn' => 50,
            'endColumn' => 61,
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
 * Capture the funds of an existing uncaptured PaymentIntent when its status is
 * <code>requires_capture</code>.
 *
 * Uncaptured PaymentIntents are cancelled a set number of days (7 by default)
 * after their creation.
 *
 * Learn more about <a href="/docs/payments/capture-later">separate authorization
 * and capture</a>.
 *
 * @param string $id
 * @param null|array{amount_to_capture?: int, application_fee_amount?: int, expand?: string[], final_capture?: bool, metadata?: null|array<string, string>, statement_descriptor?: string, statement_descriptor_suffix?: string, transfer_data?: array{amount?: int}} $params
 * @param null|RequestOptionsArray|\\Stripe\\Util\\RequestOptions $opts
 *
 * @return \\Stripe\\PaymentIntent
 *
 * @throws \\Stripe\\Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 92,
        'endLine' => 95,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe\\Service',
        'declaringClassName' => 'Stripe\\Service\\PaymentIntentService',
        'implementingClassName' => 'Stripe\\Service\\PaymentIntentService',
        'currentClassName' => 'Stripe\\Service\\PaymentIntentService',
        'aliasName' => NULL,
      ),
      'confirm' => 
      array (
        'name' => 'confirm',
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
            'startLine' => 138,
            'endLine' => 138,
            'startColumn' => 29,
            'endColumn' => 31,
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
                'startLine' => 138,
                'endLine' => 138,
                'startTokenPos' => 249,
                'startFilePos' => 14816,
                'endTokenPos' => 249,
                'endFilePos' => 14819,
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
            'startColumn' => 34,
            'endColumn' => 47,
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
                'startLine' => 138,
                'endLine' => 138,
                'startTokenPos' => 256,
                'startFilePos' => 14830,
                'endTokenPos' => 256,
                'endFilePos' => 14833,
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
            'startColumn' => 50,
            'endColumn' => 61,
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
 * Confirm that your customer intends to pay with current or provided payment
 * method. Upon confirmation, the PaymentIntent will attempt to initiate a payment.
 *
 * If the selected payment method requires additional authentication steps, the
 * PaymentIntent will transition to the <code>requires_action</code> status and
 * suggest additional actions via <code>next_action</code>. If payment fails, the
 * PaymentIntent transitions to the <code>requires_payment_method</code> status or
 * the <code>canceled</code> status if the confirmation limit is reached. If
 * payment succeeds, the PaymentIntent will transition to the
 * <code>succeeded</code> status (or <code>requires_capture</code>, if
 * <code>capture_method</code> is set to <code>manual</code>).
 *
 * If the <code>confirmation_method</code> is <code>automatic</code>, payment may
 * be attempted using our <a
 * href="/docs/stripe-js/reference#stripe-handle-card-payment">client SDKs</a> and
 * the PaymentIntent’s <a
 * href="#payment_intent_object-client_secret">client_secret</a>. After
 * <code>next_action</code>s are handled by the client, no additional confirmation
 * is required to complete the payment.
 *
 * If the <code>confirmation_method</code> is <code>manual</code>, all payment
 * attempts must be initiated using a secret key.
 *
 * If any actions are required for the payment, the PaymentIntent will return to
 * the <code>requires_confirmation</code> state after those actions are completed.
 * Your server needs to then explicitly re-confirm the PaymentIntent to initiate
 * the next payment attempt.
 *
 * There is a variable upper limit on how many times a PaymentIntent can be
 * confirmed. After this limit is reached, any further calls to this endpoint will
 * transition the PaymentIntent to the <code>canceled</code> state.
 *
 * @param string $id
 * @param null|array{capture_method?: string, confirmation_token?: string, error_on_requires_action?: bool, expand?: string[], mandate?: string, mandate_data?: null|array{customer_acceptance?: array{accepted_at?: int, offline?: array{}, online?: array{ip_address?: string, user_agent?: string}, type: string}}, off_session?: array|bool|string, payment_method?: string, payment_method_data?: array{acss_debit?: array{account_number: string, institution_number: string, transit_number: string}, affirm?: array{}, afterpay_clearpay?: array{}, alipay?: array{}, allow_redisplay?: string, alma?: array{}, amazon_pay?: array{}, au_becs_debit?: array{account_number: string, bsb_number: string}, bacs_debit?: array{account_number?: string, sort_code?: string}, bancontact?: array{}, billie?: array{}, billing_details?: array{address?: null|array{city?: string, country?: string, line1?: string, line2?: string, postal_code?: string, state?: string}, email?: null|string, name?: null|string, phone?: null|string, tax_id?: string}, blik?: array{}, boleto?: array{tax_id: string}, cashapp?: array{}, crypto?: array{}, customer_balance?: array{}, eps?: array{bank?: string}, fpx?: array{account_holder_type?: string, bank: string}, giropay?: array{}, grabpay?: array{}, ideal?: array{bank?: string}, interac_present?: array{}, kakao_pay?: array{}, klarna?: array{dob?: array{day: int, month: int, year: int}}, konbini?: array{}, kr_card?: array{}, link?: array{}, metadata?: array<string, string>, mobilepay?: array{}, multibanco?: array{}, naver_pay?: array{funding?: string}, nz_bank_account?: array{account_holder_name?: string, account_number: string, bank_code: string, branch_code: string, reference?: string, suffix: string}, oxxo?: array{}, p24?: array{bank?: string}, pay_by_bank?: array{}, payco?: array{}, paynow?: array{}, paypal?: array{}, pix?: array{}, promptpay?: array{}, radar_options?: array{session?: string}, revolut_pay?: array{}, samsung_pay?: array{}, satispay?: array{}, sepa_debit?: array{iban: string}, sofort?: array{country: string}, swish?: array{}, twint?: array{}, type: string, us_bank_account?: array{account_holder_type?: string, account_number?: string, account_type?: string, financial_connections_account?: string, routing_number?: string}, wechat_pay?: array{}, zip?: array{}}, payment_method_options?: array{acss_debit?: null|array{mandate_options?: array{custom_mandate_url?: null|string, interval_description?: string, payment_schedule?: string, transaction_type?: string}, setup_future_usage?: null|string, target_date?: string, verification_method?: string}, affirm?: null|array{capture_method?: null|string, preferred_locale?: string, setup_future_usage?: string}, afterpay_clearpay?: null|array{capture_method?: null|string, reference?: string, setup_future_usage?: string}, alipay?: null|array{setup_future_usage?: null|string}, alma?: null|array{capture_method?: null|string}, amazon_pay?: null|array{capture_method?: null|string, setup_future_usage?: null|string}, au_becs_debit?: null|array{setup_future_usage?: null|string, target_date?: string}, bacs_debit?: null|array{mandate_options?: array{reference_prefix?: null|string}, setup_future_usage?: null|string, target_date?: string}, bancontact?: null|array{preferred_language?: string, setup_future_usage?: null|string}, billie?: null|array{capture_method?: null|string}, blik?: null|array{code?: string, setup_future_usage?: null|string}, boleto?: null|array{expires_after_days?: int, setup_future_usage?: null|string}, card?: null|array{capture_method?: null|string, cvc_token?: string, installments?: array{enabled?: bool, plan?: null|array{count?: int, interval?: string, type: string}}, mandate_options?: array{amount: int, amount_type: string, description?: string, end_date?: int, interval: string, interval_count?: int, reference: string, start_date: int, supported_types?: string[]}, moto?: bool, network?: string, request_extended_authorization?: string, request_incremental_authorization?: string, request_multicapture?: string, request_overcapture?: string, request_three_d_secure?: string, require_cvc_recollection?: bool, setup_future_usage?: null|string, statement_descriptor_suffix_kana?: null|string, statement_descriptor_suffix_kanji?: null|string, three_d_secure?: array{ares_trans_status?: string, cryptogram: string, electronic_commerce_indicator?: string, exemption_indicator?: string, network_options?: array{cartes_bancaires?: array{cb_avalgo: string, cb_exemption?: string, cb_score?: int}}, requestor_challenge_indicator?: string, transaction_id: string, version: string}}, card_present?: null|array{request_extended_authorization?: bool, request_incremental_authorization_support?: bool, routing?: array{requested_priority?: string}}, cashapp?: null|array{capture_method?: null|string, setup_future_usage?: null|string}, crypto?: null|array{setup_future_usage?: string}, customer_balance?: null|array{bank_transfer?: array{eu_bank_transfer?: array{country: string}, requested_address_types?: string[], type: string}, funding_type?: string, setup_future_usage?: string}, eps?: null|array{setup_future_usage?: string}, fpx?: null|array{setup_future_usage?: string}, giropay?: null|array{setup_future_usage?: string}, grabpay?: null|array{setup_future_usage?: string}, ideal?: null|array{setup_future_usage?: null|string}, interac_present?: null|array{}, kakao_pay?: null|array{capture_method?: null|string, setup_future_usage?: null|string}, klarna?: null|array{capture_method?: null|string, on_demand?: array{average_amount?: int, maximum_amount?: int, minimum_amount?: int, purchase_interval?: string, purchase_interval_count?: int}, preferred_locale?: string, setup_future_usage?: string, subscriptions?: null|array{interval: string, interval_count?: int, name?: string, next_billing?: array{amount: int, date: string}, reference: string}[]}, konbini?: null|array{confirmation_number?: null|string, expires_after_days?: null|int, expires_at?: null|int, product_description?: null|string, setup_future_usage?: string}, kr_card?: null|array{capture_method?: null|string, setup_future_usage?: null|string}, link?: null|array{capture_method?: null|string, persistent_token?: string, setup_future_usage?: null|string}, mobilepay?: null|array{capture_method?: null|string, setup_future_usage?: string}, multibanco?: null|array{setup_future_usage?: string}, naver_pay?: null|array{capture_method?: null|string, setup_future_usage?: null|string}, nz_bank_account?: null|array{setup_future_usage?: null|string, target_date?: string}, oxxo?: null|array{expires_after_days?: int, setup_future_usage?: string}, p24?: null|array{setup_future_usage?: string, tos_shown_and_accepted?: bool}, pay_by_bank?: null|array{}, payco?: null|array{capture_method?: null|string}, paynow?: null|array{setup_future_usage?: string}, paypal?: null|array{capture_method?: null|string, preferred_locale?: string, reference?: string, risk_correlation_id?: string, setup_future_usage?: null|string}, pix?: null|array{amount_includes_iof?: string, expires_after_seconds?: int, expires_at?: int, setup_future_usage?: string}, promptpay?: null|array{setup_future_usage?: string}, revolut_pay?: null|array{capture_method?: null|string, setup_future_usage?: null|string}, samsung_pay?: null|array{capture_method?: null|string}, satispay?: null|array{capture_method?: null|string}, sepa_debit?: null|array{mandate_options?: array{reference_prefix?: null|string}, setup_future_usage?: null|string, target_date?: string}, sofort?: null|array{preferred_language?: null|string, setup_future_usage?: null|string}, swish?: null|array{reference?: null|string, setup_future_usage?: string}, twint?: null|array{setup_future_usage?: string}, us_bank_account?: null|array{financial_connections?: array{filters?: array{account_subcategories?: string[]}, permissions?: string[], prefetch?: string[], return_url?: string}, mandate_options?: array{collection_method?: null|string}, networks?: array{requested?: string[]}, preferred_settlement_speed?: null|string, setup_future_usage?: null|string, target_date?: string, verification_method?: string}, wechat_pay?: null|array{app_id?: string, client?: string, setup_future_usage?: string}, zip?: null|array{setup_future_usage?: string}}, payment_method_types?: string[], radar_options?: array{session?: string}, receipt_email?: null|string, return_url?: string, setup_future_usage?: null|string, shipping?: null|array{address: array{city?: string, country?: string, line1?: string, line2?: string, postal_code?: string, state?: string}, carrier?: string, name: string, phone?: string, tracking_number?: string}, use_stripe_sdk?: bool} $params
 * @param null|RequestOptionsArray|\\Stripe\\Util\\RequestOptions $opts
 *
 * @return \\Stripe\\PaymentIntent
 *
 * @throws \\Stripe\\Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 138,
        'endLine' => 141,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe\\Service',
        'declaringClassName' => 'Stripe\\Service\\PaymentIntentService',
        'implementingClassName' => 'Stripe\\Service\\PaymentIntentService',
        'currentClassName' => 'Stripe\\Service\\PaymentIntentService',
        'aliasName' => NULL,
      ),
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
                'startLine' => 163,
                'endLine' => 163,
                'startTokenPos' => 302,
                'startFilePos' => 25033,
                'endTokenPos' => 302,
                'endFilePos' => 25036,
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
                'startTokenPos' => 309,
                'startFilePos' => 25047,
                'endTokenPos' => 309,
                'endFilePos' => 25050,
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
 * Creates a PaymentIntent object.
 *
 * After the PaymentIntent is created, attach a payment method and <a
 * href="/docs/api/payment_intents/confirm">confirm</a> to continue the payment.
 * Learn more about <a href="/docs/payments/payment-intents">the available payment
 * flows with the Payment Intents API</a>.
 *
 * When you use <code>confirm=true</code> during creation, it’s equivalent to
 * creating and confirming the PaymentIntent in the same call. You can use any
 * parameters available in the <a href="/docs/api/payment_intents/confirm">confirm
 * API</a> when you supply <code>confirm=true</code>.
 *
 * @param null|array{amount: int, application_fee_amount?: int, automatic_payment_methods?: array{allow_redirects?: string, enabled: bool}, capture_method?: string, confirm?: bool, confirmation_method?: string, confirmation_token?: string, currency: string, customer?: string, description?: string, error_on_requires_action?: bool, excluded_payment_method_types?: string[], expand?: string[], mandate?: string, mandate_data?: null|array{customer_acceptance: array{accepted_at?: int, offline?: array{}, online?: array{ip_address: string, user_agent: string}, type: string}}, metadata?: array<string, string>, off_session?: array|bool|string, on_behalf_of?: string, payment_method?: string, payment_method_configuration?: string, payment_method_data?: array{acss_debit?: array{account_number: string, institution_number: string, transit_number: string}, affirm?: array{}, afterpay_clearpay?: array{}, alipay?: array{}, allow_redisplay?: string, alma?: array{}, amazon_pay?: array{}, au_becs_debit?: array{account_number: string, bsb_number: string}, bacs_debit?: array{account_number?: string, sort_code?: string}, bancontact?: array{}, billie?: array{}, billing_details?: array{address?: null|array{city?: string, country?: string, line1?: string, line2?: string, postal_code?: string, state?: string}, email?: null|string, name?: null|string, phone?: null|string, tax_id?: string}, blik?: array{}, boleto?: array{tax_id: string}, cashapp?: array{}, crypto?: array{}, customer_balance?: array{}, eps?: array{bank?: string}, fpx?: array{account_holder_type?: string, bank: string}, giropay?: array{}, grabpay?: array{}, ideal?: array{bank?: string}, interac_present?: array{}, kakao_pay?: array{}, klarna?: array{dob?: array{day: int, month: int, year: int}}, konbini?: array{}, kr_card?: array{}, link?: array{}, metadata?: array<string, string>, mobilepay?: array{}, multibanco?: array{}, naver_pay?: array{funding?: string}, nz_bank_account?: array{account_holder_name?: string, account_number: string, bank_code: string, branch_code: string, reference?: string, suffix: string}, oxxo?: array{}, p24?: array{bank?: string}, pay_by_bank?: array{}, payco?: array{}, paynow?: array{}, paypal?: array{}, pix?: array{}, promptpay?: array{}, radar_options?: array{session?: string}, revolut_pay?: array{}, samsung_pay?: array{}, satispay?: array{}, sepa_debit?: array{iban: string}, sofort?: array{country: string}, swish?: array{}, twint?: array{}, type: string, us_bank_account?: array{account_holder_type?: string, account_number?: string, account_type?: string, financial_connections_account?: string, routing_number?: string}, wechat_pay?: array{}, zip?: array{}}, payment_method_options?: array{acss_debit?: null|array{mandate_options?: array{custom_mandate_url?: null|string, interval_description?: string, payment_schedule?: string, transaction_type?: string}, setup_future_usage?: null|string, target_date?: string, verification_method?: string}, affirm?: null|array{capture_method?: null|string, preferred_locale?: string, setup_future_usage?: string}, afterpay_clearpay?: null|array{capture_method?: null|string, reference?: string, setup_future_usage?: string}, alipay?: null|array{setup_future_usage?: null|string}, alma?: null|array{capture_method?: null|string}, amazon_pay?: null|array{capture_method?: null|string, setup_future_usage?: null|string}, au_becs_debit?: null|array{setup_future_usage?: null|string, target_date?: string}, bacs_debit?: null|array{mandate_options?: array{reference_prefix?: null|string}, setup_future_usage?: null|string, target_date?: string}, bancontact?: null|array{preferred_language?: string, setup_future_usage?: null|string}, billie?: null|array{capture_method?: null|string}, blik?: null|array{code?: string, setup_future_usage?: null|string}, boleto?: null|array{expires_after_days?: int, setup_future_usage?: null|string}, card?: null|array{capture_method?: null|string, cvc_token?: string, installments?: array{enabled?: bool, plan?: null|array{count?: int, interval?: string, type: string}}, mandate_options?: array{amount: int, amount_type: string, description?: string, end_date?: int, interval: string, interval_count?: int, reference: string, start_date: int, supported_types?: string[]}, moto?: bool, network?: string, request_extended_authorization?: string, request_incremental_authorization?: string, request_multicapture?: string, request_overcapture?: string, request_three_d_secure?: string, require_cvc_recollection?: bool, setup_future_usage?: null|string, statement_descriptor_suffix_kana?: null|string, statement_descriptor_suffix_kanji?: null|string, three_d_secure?: array{ares_trans_status?: string, cryptogram: string, electronic_commerce_indicator?: string, exemption_indicator?: string, network_options?: array{cartes_bancaires?: array{cb_avalgo: string, cb_exemption?: string, cb_score?: int}}, requestor_challenge_indicator?: string, transaction_id: string, version: string}}, card_present?: null|array{request_extended_authorization?: bool, request_incremental_authorization_support?: bool, routing?: array{requested_priority?: string}}, cashapp?: null|array{capture_method?: null|string, setup_future_usage?: null|string}, crypto?: null|array{setup_future_usage?: string}, customer_balance?: null|array{bank_transfer?: array{eu_bank_transfer?: array{country: string}, requested_address_types?: string[], type: string}, funding_type?: string, setup_future_usage?: string}, eps?: null|array{setup_future_usage?: string}, fpx?: null|array{setup_future_usage?: string}, giropay?: null|array{setup_future_usage?: string}, grabpay?: null|array{setup_future_usage?: string}, ideal?: null|array{setup_future_usage?: null|string}, interac_present?: null|array{}, kakao_pay?: null|array{capture_method?: null|string, setup_future_usage?: null|string}, klarna?: null|array{capture_method?: null|string, on_demand?: array{average_amount?: int, maximum_amount?: int, minimum_amount?: int, purchase_interval?: string, purchase_interval_count?: int}, preferred_locale?: string, setup_future_usage?: string, subscriptions?: null|array{interval: string, interval_count?: int, name?: string, next_billing?: array{amount: int, date: string}, reference: string}[]}, konbini?: null|array{confirmation_number?: null|string, expires_after_days?: null|int, expires_at?: null|int, product_description?: null|string, setup_future_usage?: string}, kr_card?: null|array{capture_method?: null|string, setup_future_usage?: null|string}, link?: null|array{capture_method?: null|string, persistent_token?: string, setup_future_usage?: null|string}, mobilepay?: null|array{capture_method?: null|string, setup_future_usage?: string}, multibanco?: null|array{setup_future_usage?: string}, naver_pay?: null|array{capture_method?: null|string, setup_future_usage?: null|string}, nz_bank_account?: null|array{setup_future_usage?: null|string, target_date?: string}, oxxo?: null|array{expires_after_days?: int, setup_future_usage?: string}, p24?: null|array{setup_future_usage?: string, tos_shown_and_accepted?: bool}, pay_by_bank?: null|array{}, payco?: null|array{capture_method?: null|string}, paynow?: null|array{setup_future_usage?: string}, paypal?: null|array{capture_method?: null|string, preferred_locale?: string, reference?: string, risk_correlation_id?: string, setup_future_usage?: null|string}, pix?: null|array{amount_includes_iof?: string, expires_after_seconds?: int, expires_at?: int, setup_future_usage?: string}, promptpay?: null|array{setup_future_usage?: string}, revolut_pay?: null|array{capture_method?: null|string, setup_future_usage?: null|string}, samsung_pay?: null|array{capture_method?: null|string}, satispay?: null|array{capture_method?: null|string}, sepa_debit?: null|array{mandate_options?: array{reference_prefix?: null|string}, setup_future_usage?: null|string, target_date?: string}, sofort?: null|array{preferred_language?: null|string, setup_future_usage?: null|string}, swish?: null|array{reference?: null|string, setup_future_usage?: string}, twint?: null|array{setup_future_usage?: string}, us_bank_account?: null|array{financial_connections?: array{filters?: array{account_subcategories?: string[]}, permissions?: string[], prefetch?: string[], return_url?: string}, mandate_options?: array{collection_method?: null|string}, networks?: array{requested?: string[]}, preferred_settlement_speed?: null|string, setup_future_usage?: null|string, target_date?: string, verification_method?: string}, wechat_pay?: null|array{app_id?: string, client?: string, setup_future_usage?: string}, zip?: null|array{setup_future_usage?: string}}, payment_method_types?: string[], radar_options?: array{session?: string}, receipt_email?: string, return_url?: string, setup_future_usage?: string, shipping?: array{address: array{city?: string, country?: string, line1?: string, line2?: string, postal_code?: string, state?: string}, carrier?: string, name: string, phone?: string, tracking_number?: string}, statement_descriptor?: string, statement_descriptor_suffix?: string, transfer_data?: array{amount?: int, destination: string}, transfer_group?: string, use_stripe_sdk?: bool} $params
 * @param null|RequestOptionsArray|\\Stripe\\Util\\RequestOptions $opts
 *
 * @return \\Stripe\\PaymentIntent
 *
 * @throws \\Stripe\\Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 163,
        'endLine' => 166,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe\\Service',
        'declaringClassName' => 'Stripe\\Service\\PaymentIntentService',
        'implementingClassName' => 'Stripe\\Service\\PaymentIntentService',
        'currentClassName' => 'Stripe\\Service\\PaymentIntentService',
        'aliasName' => NULL,
      ),
      'incrementAuthorization' => 
      array (
        'name' => 'incrementAuthorization',
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
            'startLine' => 205,
            'endLine' => 205,
            'startColumn' => 44,
            'endColumn' => 46,
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
                'startLine' => 205,
                'endLine' => 205,
                'startTokenPos' => 350,
                'startFilePos' => 27287,
                'endTokenPos' => 350,
                'endFilePos' => 27290,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 205,
            'endLine' => 205,
            'startColumn' => 49,
            'endColumn' => 62,
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
                'startLine' => 205,
                'endLine' => 205,
                'startTokenPos' => 357,
                'startFilePos' => 27301,
                'endTokenPos' => 357,
                'endFilePos' => 27304,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 205,
            'endLine' => 205,
            'startColumn' => 65,
            'endColumn' => 76,
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
 * Perform an incremental authorization on an eligible <a
 * href="/docs/api/payment_intents/object">PaymentIntent</a>. To be eligible, the
 * PaymentIntent’s status must be <code>requires_capture</code> and <a
 * href="/docs/api/charges/object#charge_object-payment_method_details-card_present-incremental_authorization_supported">incremental_authorization_supported</a>
 * must be <code>true</code>.
 *
 * Incremental authorizations attempt to increase the authorized amount on your
 * customer’s card to the new, higher <code>amount</code> provided. Similar to the
 * initial authorization, incremental authorizations can be declined. A single
 * PaymentIntent can call this endpoint multiple times to further increase the
 * authorized amount.
 *
 * If the incremental authorization succeeds, the PaymentIntent object returns with
 * the updated <a
 * href="/docs/api/payment_intents/object#payment_intent_object-amount">amount</a>.
 * If the incremental authorization fails, a <a
 * href="/docs/error-codes#card-declined">card_declined</a> error returns, and no
 * other fields on the PaymentIntent or Charge update. The PaymentIntent object
 * remains capturable for the previously authorized amount.
 *
 * Each PaymentIntent can have a maximum of 10 incremental authorization attempts,
 * including declines. After it’s captured, a PaymentIntent can no longer be
 * incremented.
 *
 * Learn more about <a
 * href="/docs/terminal/features/incremental-authorizations">incremental
 * authorizations</a>.
 *
 * @param string $id
 * @param null|array{amount: int, application_fee_amount?: int, description?: string, expand?: string[], metadata?: array<string, string>, statement_descriptor?: string, transfer_data?: array{amount?: int}} $params
 * @param null|RequestOptionsArray|\\Stripe\\Util\\RequestOptions $opts
 *
 * @return \\Stripe\\PaymentIntent
 *
 * @throws \\Stripe\\Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 205,
        'endLine' => 208,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe\\Service',
        'declaringClassName' => 'Stripe\\Service\\PaymentIntentService',
        'implementingClassName' => 'Stripe\\Service\\PaymentIntentService',
        'currentClassName' => 'Stripe\\Service\\PaymentIntentService',
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
            'startLine' => 228,
            'endLine' => 228,
            'startColumn' => 30,
            'endColumn' => 32,
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
                'startLine' => 228,
                'endLine' => 228,
                'startTokenPos' => 406,
                'startFilePos' => 28268,
                'endTokenPos' => 406,
                'endFilePos' => 28271,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 228,
            'endLine' => 228,
            'startColumn' => 35,
            'endColumn' => 48,
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
                'startLine' => 228,
                'endLine' => 228,
                'startTokenPos' => 413,
                'startFilePos' => 28282,
                'endTokenPos' => 413,
                'endFilePos' => 28285,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 228,
            'endLine' => 228,
            'startColumn' => 51,
            'endColumn' => 62,
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
 * Retrieves the details of a PaymentIntent that has previously been created.
 *
 * You can retrieve a PaymentIntent client-side using a publishable key when the
 * <code>client_secret</code> is in the query string.
 *
 * If you retrieve a PaymentIntent with a publishable key, it only returns a subset
 * of properties. Refer to the <a href="#payment_intent_object">payment intent</a>
 * object reference for more details.
 *
 * @param string $id
 * @param null|array{client_secret?: string, expand?: string[]} $params
 * @param null|RequestOptionsArray|\\Stripe\\Util\\RequestOptions $opts
 *
 * @return \\Stripe\\PaymentIntent
 *
 * @throws \\Stripe\\Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 228,
        'endLine' => 231,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe\\Service',
        'declaringClassName' => 'Stripe\\Service\\PaymentIntentService',
        'implementingClassName' => 'Stripe\\Service\\PaymentIntentService',
        'currentClassName' => 'Stripe\\Service\\PaymentIntentService',
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
                'startLine' => 248,
                'endLine' => 248,
                'startTokenPos' => 459,
                'startFilePos' => 29277,
                'endTokenPos' => 459,
                'endFilePos' => 29280,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 248,
            'endLine' => 248,
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
                'startLine' => 248,
                'endLine' => 248,
                'startTokenPos' => 466,
                'startFilePos' => 29291,
                'endTokenPos' => 466,
                'endFilePos' => 29294,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 248,
            'endLine' => 248,
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
 * Search for PaymentIntents you’ve previously created using Stripe’s <a
 * href="/docs/search#search-query-language">Search Query Language</a>. Don’t use
 * search in read-after-write flows where strict consistency is necessary. Under
 * normal operating conditions, data is searchable in less than a minute.
 * Occasionally, propagation of new or updated data can be up to an hour behind
 * during outages. Search functionality is not available to merchants in India.
 *
 * @param null|array{expand?: string[], limit?: int, page?: string, query: string} $params
 * @param null|RequestOptionsArray|\\Stripe\\Util\\RequestOptions $opts
 *
 * @return \\Stripe\\SearchResult<\\Stripe\\PaymentIntent>
 *
 * @throws \\Stripe\\Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 248,
        'endLine' => 251,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe\\Service',
        'declaringClassName' => 'Stripe\\Service\\PaymentIntentService',
        'implementingClassName' => 'Stripe\\Service\\PaymentIntentService',
        'currentClassName' => 'Stripe\\Service\\PaymentIntentService',
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
            'startLine' => 270,
            'endLine' => 270,
            'startColumn' => 28,
            'endColumn' => 30,
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
                'startLine' => 270,
                'endLine' => 270,
                'startTokenPos' => 507,
                'startFilePos' => 38793,
                'endTokenPos' => 507,
                'endFilePos' => 38796,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 270,
            'endLine' => 270,
            'startColumn' => 33,
            'endColumn' => 46,
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
                'startLine' => 270,
                'endLine' => 270,
                'startTokenPos' => 514,
                'startFilePos' => 38807,
                'endTokenPos' => 514,
                'endFilePos' => 38810,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 270,
            'endLine' => 270,
            'startColumn' => 49,
            'endColumn' => 60,
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
 * Updates properties on a PaymentIntent object without confirming.
 *
 * Depending on which properties you update, you might need to confirm the
 * PaymentIntent again. For example, updating the <code>payment_method</code>
 * always requires you to confirm the PaymentIntent again. If you prefer to update
 * and confirm at the same time, we recommend updating properties through the <a
 * href="/docs/api/payment_intents/confirm">confirm API</a> instead.
 *
 * @param string $id
 * @param null|array{amount?: int, application_fee_amount?: null|int, capture_method?: string, currency?: string, customer?: string, description?: string, expand?: string[], metadata?: null|array<string, string>, payment_method?: string, payment_method_configuration?: string, payment_method_data?: array{acss_debit?: array{account_number: string, institution_number: string, transit_number: string}, affirm?: array{}, afterpay_clearpay?: array{}, alipay?: array{}, allow_redisplay?: string, alma?: array{}, amazon_pay?: array{}, au_becs_debit?: array{account_number: string, bsb_number: string}, bacs_debit?: array{account_number?: string, sort_code?: string}, bancontact?: array{}, billie?: array{}, billing_details?: array{address?: null|array{city?: string, country?: string, line1?: string, line2?: string, postal_code?: string, state?: string}, email?: null|string, name?: null|string, phone?: null|string, tax_id?: string}, blik?: array{}, boleto?: array{tax_id: string}, cashapp?: array{}, crypto?: array{}, customer_balance?: array{}, eps?: array{bank?: string}, fpx?: array{account_holder_type?: string, bank: string}, giropay?: array{}, grabpay?: array{}, ideal?: array{bank?: string}, interac_present?: array{}, kakao_pay?: array{}, klarna?: array{dob?: array{day: int, month: int, year: int}}, konbini?: array{}, kr_card?: array{}, link?: array{}, metadata?: array<string, string>, mobilepay?: array{}, multibanco?: array{}, naver_pay?: array{funding?: string}, nz_bank_account?: array{account_holder_name?: string, account_number: string, bank_code: string, branch_code: string, reference?: string, suffix: string}, oxxo?: array{}, p24?: array{bank?: string}, pay_by_bank?: array{}, payco?: array{}, paynow?: array{}, paypal?: array{}, pix?: array{}, promptpay?: array{}, radar_options?: array{session?: string}, revolut_pay?: array{}, samsung_pay?: array{}, satispay?: array{}, sepa_debit?: array{iban: string}, sofort?: array{country: string}, swish?: array{}, twint?: array{}, type: string, us_bank_account?: array{account_holder_type?: string, account_number?: string, account_type?: string, financial_connections_account?: string, routing_number?: string}, wechat_pay?: array{}, zip?: array{}}, payment_method_options?: array{acss_debit?: null|array{mandate_options?: array{custom_mandate_url?: null|string, interval_description?: string, payment_schedule?: string, transaction_type?: string}, setup_future_usage?: null|string, target_date?: string, verification_method?: string}, affirm?: null|array{capture_method?: null|string, preferred_locale?: string, setup_future_usage?: string}, afterpay_clearpay?: null|array{capture_method?: null|string, reference?: string, setup_future_usage?: string}, alipay?: null|array{setup_future_usage?: null|string}, alma?: null|array{capture_method?: null|string}, amazon_pay?: null|array{capture_method?: null|string, setup_future_usage?: null|string}, au_becs_debit?: null|array{setup_future_usage?: null|string, target_date?: string}, bacs_debit?: null|array{mandate_options?: array{reference_prefix?: null|string}, setup_future_usage?: null|string, target_date?: string}, bancontact?: null|array{preferred_language?: string, setup_future_usage?: null|string}, billie?: null|array{capture_method?: null|string}, blik?: null|array{code?: string, setup_future_usage?: null|string}, boleto?: null|array{expires_after_days?: int, setup_future_usage?: null|string}, card?: null|array{capture_method?: null|string, cvc_token?: string, installments?: array{enabled?: bool, plan?: null|array{count?: int, interval?: string, type: string}}, mandate_options?: array{amount: int, amount_type: string, description?: string, end_date?: int, interval: string, interval_count?: int, reference: string, start_date: int, supported_types?: string[]}, moto?: bool, network?: string, request_extended_authorization?: string, request_incremental_authorization?: string, request_multicapture?: string, request_overcapture?: string, request_three_d_secure?: string, require_cvc_recollection?: bool, setup_future_usage?: null|string, statement_descriptor_suffix_kana?: null|string, statement_descriptor_suffix_kanji?: null|string, three_d_secure?: array{ares_trans_status?: string, cryptogram: string, electronic_commerce_indicator?: string, exemption_indicator?: string, network_options?: array{cartes_bancaires?: array{cb_avalgo: string, cb_exemption?: string, cb_score?: int}}, requestor_challenge_indicator?: string, transaction_id: string, version: string}}, card_present?: null|array{request_extended_authorization?: bool, request_incremental_authorization_support?: bool, routing?: array{requested_priority?: string}}, cashapp?: null|array{capture_method?: null|string, setup_future_usage?: null|string}, crypto?: null|array{setup_future_usage?: string}, customer_balance?: null|array{bank_transfer?: array{eu_bank_transfer?: array{country: string}, requested_address_types?: string[], type: string}, funding_type?: string, setup_future_usage?: string}, eps?: null|array{setup_future_usage?: string}, fpx?: null|array{setup_future_usage?: string}, giropay?: null|array{setup_future_usage?: string}, grabpay?: null|array{setup_future_usage?: string}, ideal?: null|array{setup_future_usage?: null|string}, interac_present?: null|array{}, kakao_pay?: null|array{capture_method?: null|string, setup_future_usage?: null|string}, klarna?: null|array{capture_method?: null|string, on_demand?: array{average_amount?: int, maximum_amount?: int, minimum_amount?: int, purchase_interval?: string, purchase_interval_count?: int}, preferred_locale?: string, setup_future_usage?: string, subscriptions?: null|array{interval: string, interval_count?: int, name?: string, next_billing?: array{amount: int, date: string}, reference: string}[]}, konbini?: null|array{confirmation_number?: null|string, expires_after_days?: null|int, expires_at?: null|int, product_description?: null|string, setup_future_usage?: string}, kr_card?: null|array{capture_method?: null|string, setup_future_usage?: null|string}, link?: null|array{capture_method?: null|string, persistent_token?: string, setup_future_usage?: null|string}, mobilepay?: null|array{capture_method?: null|string, setup_future_usage?: string}, multibanco?: null|array{setup_future_usage?: string}, naver_pay?: null|array{capture_method?: null|string, setup_future_usage?: null|string}, nz_bank_account?: null|array{setup_future_usage?: null|string, target_date?: string}, oxxo?: null|array{expires_after_days?: int, setup_future_usage?: string}, p24?: null|array{setup_future_usage?: string, tos_shown_and_accepted?: bool}, pay_by_bank?: null|array{}, payco?: null|array{capture_method?: null|string}, paynow?: null|array{setup_future_usage?: string}, paypal?: null|array{capture_method?: null|string, preferred_locale?: string, reference?: string, risk_correlation_id?: string, setup_future_usage?: null|string}, pix?: null|array{amount_includes_iof?: string, expires_after_seconds?: int, expires_at?: int, setup_future_usage?: string}, promptpay?: null|array{setup_future_usage?: string}, revolut_pay?: null|array{capture_method?: null|string, setup_future_usage?: null|string}, samsung_pay?: null|array{capture_method?: null|string}, satispay?: null|array{capture_method?: null|string}, sepa_debit?: null|array{mandate_options?: array{reference_prefix?: null|string}, setup_future_usage?: null|string, target_date?: string}, sofort?: null|array{preferred_language?: null|string, setup_future_usage?: null|string}, swish?: null|array{reference?: null|string, setup_future_usage?: string}, twint?: null|array{setup_future_usage?: string}, us_bank_account?: null|array{financial_connections?: array{filters?: array{account_subcategories?: string[]}, permissions?: string[], prefetch?: string[], return_url?: string}, mandate_options?: array{collection_method?: null|string}, networks?: array{requested?: string[]}, preferred_settlement_speed?: null|string, setup_future_usage?: null|string, target_date?: string, verification_method?: string}, wechat_pay?: null|array{app_id?: string, client?: string, setup_future_usage?: string}, zip?: null|array{setup_future_usage?: string}}, payment_method_types?: string[], receipt_email?: null|string, setup_future_usage?: null|string, shipping?: null|array{address: array{city?: string, country?: string, line1?: string, line2?: string, postal_code?: string, state?: string}, carrier?: string, name: string, phone?: string, tracking_number?: string}, statement_descriptor?: string, statement_descriptor_suffix?: string, transfer_data?: array{amount?: int}, transfer_group?: string} $params
 * @param null|RequestOptionsArray|\\Stripe\\Util\\RequestOptions $opts
 *
 * @return \\Stripe\\PaymentIntent
 *
 * @throws \\Stripe\\Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 270,
        'endLine' => 273,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe\\Service',
        'declaringClassName' => 'Stripe\\Service\\PaymentIntentService',
        'implementingClassName' => 'Stripe\\Service\\PaymentIntentService',
        'currentClassName' => 'Stripe\\Service\\PaymentIntentService',
        'aliasName' => NULL,
      ),
      'verifyMicrodeposits' => 
      array (
        'name' => 'verifyMicrodeposits',
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
            'startLine' => 286,
            'endLine' => 286,
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
                'startLine' => 286,
                'endLine' => 286,
                'startTokenPos' => 563,
                'startFilePos' => 39381,
                'endTokenPos' => 563,
                'endFilePos' => 39384,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 286,
            'endLine' => 286,
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
                'startLine' => 286,
                'endLine' => 286,
                'startTokenPos' => 570,
                'startFilePos' => 39395,
                'endTokenPos' => 570,
                'endFilePos' => 39398,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 286,
            'endLine' => 286,
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
 * Verifies microdeposits on a PaymentIntent object.
 *
 * @param string $id
 * @param null|array{amounts?: int[], descriptor_code?: string, expand?: string[]} $params
 * @param null|RequestOptionsArray|\\Stripe\\Util\\RequestOptions $opts
 *
 * @return \\Stripe\\PaymentIntent
 *
 * @throws \\Stripe\\Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 286,
        'endLine' => 289,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe\\Service',
        'declaringClassName' => 'Stripe\\Service\\PaymentIntentService',
        'implementingClassName' => 'Stripe\\Service\\PaymentIntentService',
        'currentClassName' => 'Stripe\\Service\\PaymentIntentService',
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