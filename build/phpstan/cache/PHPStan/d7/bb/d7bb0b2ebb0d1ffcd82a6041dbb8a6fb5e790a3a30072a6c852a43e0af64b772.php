<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/Account.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Stripe\Account
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-da50007a6f8a191cf6990dd88e7e1f77f927846797dbd58ea155ae32e680e9b1-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Stripe\\Account',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/Account.php',
      ),
    ),
    'namespace' => 'Stripe',
    'name' => 'Stripe\\Account',
    'shortName' => 'Account',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * This is an object representing a Stripe account. You can retrieve it to see
 * properties on the account like its current requirements or if the account is
 * enabled to make live charges or receive payouts.
 *
 * For accounts where <a href="/api/accounts/object#account_object-controller-requirement_collection">controller.requirement_collection</a>
 * is <code>application</code>, which includes Custom accounts, the properties below are always
 * returned.
 *
 * For accounts where <a href="/api/accounts/object#account_object-controller-requirement_collection">controller.requirement_collection</a>
 * is <code>stripe</code>, which includes Standard and Express accounts, some properties are only returned
 * until you create an <a href="/api/account_links">Account Link</a> or <a href="/api/account_sessions">Account Session</a>
 * to start Connect Onboarding. Learn about the <a href="/connect/accounts">differences between accounts</a>.
 *
 * @property string $id Unique identifier for the object.
 * @property string $object String representing the object\'s type. Objects of the same type share the same value.
 * @property null|(object{annual_revenue?: null|(object{amount: null|int, currency: null|string, fiscal_year_end: null|string}&StripeObject), estimated_worker_count?: null|int, mcc: null|string, minority_owned_business_designation: null|string[], monthly_estimated_revenue?: (object{amount: int, currency: string}&StripeObject), name: null|string, product_description?: null|string, support_address: null|(object{city: null|string, country: null|string, line1: null|string, line2: null|string, postal_code: null|string, state: null|string}&StripeObject), support_email: null|string, support_phone: null|string, support_url: null|string, url: null|string}&StripeObject) $business_profile Business information about the account.
 * @property null|string $business_type The business type.
 * @property null|(object{acss_debit_payments?: string, affirm_payments?: string, afterpay_clearpay_payments?: string, alma_payments?: string, amazon_pay_payments?: string, au_becs_debit_payments?: string, bacs_debit_payments?: string, bancontact_payments?: string, bank_transfer_payments?: string, billie_payments?: string, blik_payments?: string, boleto_payments?: string, card_issuing?: string, card_payments?: string, cartes_bancaires_payments?: string, cashapp_payments?: string, crypto_payments?: string, eps_payments?: string, fpx_payments?: string, gb_bank_transfer_payments?: string, giropay_payments?: string, grabpay_payments?: string, ideal_payments?: string, india_international_payments?: string, jcb_payments?: string, jp_bank_transfer_payments?: string, kakao_pay_payments?: string, klarna_payments?: string, konbini_payments?: string, kr_card_payments?: string, legacy_payments?: string, link_payments?: string, mobilepay_payments?: string, multibanco_payments?: string, mx_bank_transfer_payments?: string, naver_pay_payments?: string, nz_bank_account_becs_debit_payments?: string, oxxo_payments?: string, p24_payments?: string, pay_by_bank_payments?: string, payco_payments?: string, paynow_payments?: string, pix_payments?: string, promptpay_payments?: string, revolut_pay_payments?: string, samsung_pay_payments?: string, satispay_payments?: string, sepa_bank_transfer_payments?: string, sepa_debit_payments?: string, sofort_payments?: string, swish_payments?: string, tax_reporting_us_1099_k?: string, tax_reporting_us_1099_misc?: string, transfers?: string, treasury?: string, twint_payments?: string, us_bank_account_ach_payments?: string, us_bank_transfer_payments?: string, zip_payments?: string}&StripeObject) $capabilities
 * @property null|bool $charges_enabled Whether the account can process charges.
 * @property null|(object{address?: (object{city: null|string, country: null|string, line1: null|string, line2: null|string, postal_code: null|string, state: null|string}&StripeObject), address_kana?: null|(object{city: null|string, country: null|string, line1: null|string, line2: null|string, postal_code: null|string, state: null|string, town: null|string}&StripeObject), address_kanji?: null|(object{city: null|string, country: null|string, line1: null|string, line2: null|string, postal_code: null|string, state: null|string, town: null|string}&StripeObject), directors_provided?: bool, directorship_declaration?: null|(object{date: null|int, ip: null|string, user_agent: null|string}&StripeObject), executives_provided?: bool, export_license_id?: string, export_purpose_code?: string, name?: null|string, name_kana?: null|string, name_kanji?: null|string, owners_provided?: bool, ownership_declaration?: null|(object{date: null|int, ip: null|string, user_agent: null|string}&StripeObject), ownership_exemption_reason?: string, phone?: null|string, registration_date?: (object{day: null|int, month: null|int, year: null|int}&StripeObject), structure?: string, tax_id_provided?: bool, tax_id_registrar?: string, vat_id_provided?: bool, verification?: null|(object{document: (object{back: null|File|string, details: null|string, details_code: null|string, front: null|File|string}&StripeObject)}&StripeObject)}&StripeObject) $company
 * @property null|(object{fees?: (object{payer: string}&StripeObject), is_controller?: bool, losses?: (object{payments: string}&StripeObject), requirement_collection?: string, stripe_dashboard?: (object{type: string}&StripeObject), type: string}&StripeObject) $controller
 * @property null|string $country The account\'s country.
 * @property null|int $created Time at which the account was connected. Measured in seconds since the Unix epoch.
 * @property null|string $default_currency Three-letter ISO currency code representing the default currency for the account. This must be a currency that <a href="https://stripe.com/docs/payouts">Stripe supports in the account\'s country</a>.
 * @property null|bool $details_submitted Whether account details have been submitted. Accounts with Stripe Dashboard access, which includes Standard accounts, cannot receive payouts before this is true. Accounts where this is false should be directed to <a href="/connect/onboarding">an onboarding flow</a> to finish submitting account details.
 * @property null|string $email An email address associated with the account. It\'s not used for authentication and Stripe doesn\'t market to this field without explicit approval from the platform.
 * @property null|Collection<BankAccount|Card> $external_accounts External accounts (bank accounts and debit cards) currently attached to this account. External accounts are only returned for requests where <code>controller[is_controller]</code> is true.
 * @property null|(object{alternatives: null|(object{alternative_fields_due: string[], original_fields_due: string[]}&StripeObject)[], current_deadline: null|int, currently_due: null|string[], disabled_reason: null|string, errors: null|(object{code: string, reason: string, requirement: string}&StripeObject)[], eventually_due: null|string[], past_due: null|string[], pending_verification: null|string[]}&StripeObject) $future_requirements
 * @property null|(object{payments_pricing: null|string}&StripeObject) $groups The groups associated with the account.
 * @property null|Person $individual <p>This is an object representing a person associated with a Stripe account.</p><p>A platform can only access a subset of data in a person for an account where <a href="/api/accounts/object#account_object-controller-requirement_collection">account.controller.requirement_collection</a> is <code>stripe</code>, which includes Standard and Express accounts, after creating an Account Link or Account Session to start Connect onboarding.</p><p>See the <a href="/connect/standard-accounts">Standard onboarding</a> or <a href="/connect/express-accounts">Express onboarding</a> documentation for information about prefilling information and account onboarding steps. Learn more about <a href="/connect/handling-api-verification#person-information">handling identity verification with the API</a>.</p>
 * @property null|StripeObject $metadata Set of <a href="https://stripe.com/docs/api/metadata">key-value pairs</a> that you can attach to an object. This can be useful for storing additional information about the object in a structured format.
 * @property null|bool $payouts_enabled Whether the funds in this account can be paid out.
 * @property null|(object{alternatives: null|(object{alternative_fields_due: string[], original_fields_due: string[]}&StripeObject)[], current_deadline: null|int, currently_due: null|string[], disabled_reason: null|string, errors: null|(object{code: string, reason: string, requirement: string}&StripeObject)[], eventually_due: null|string[], past_due: null|string[], pending_verification: null|string[]}&StripeObject) $requirements
 * @property null|(object{bacs_debit_payments?: (object{display_name: null|string, service_user_number: null|string}&StripeObject), branding: (object{icon: null|File|string, logo: null|File|string, primary_color: null|string, secondary_color: null|string}&StripeObject), card_issuing?: (object{tos_acceptance?: (object{date: null|int, ip: null|string, user_agent?: string}&StripeObject)}&StripeObject), card_payments: (object{decline_on?: (object{avs_failure: bool, cvc_failure: bool}&StripeObject), statement_descriptor_prefix: null|string, statement_descriptor_prefix_kana: null|string, statement_descriptor_prefix_kanji: null|string}&StripeObject), dashboard: (object{display_name: null|string, timezone: null|string}&StripeObject), invoices?: (object{default_account_tax_ids: null|(string|TaxId)[], hosted_payment_method_save: null|string}&StripeObject), payments: (object{statement_descriptor: null|string, statement_descriptor_kana: null|string, statement_descriptor_kanji: null|string, statement_descriptor_prefix_kana: null|string, statement_descriptor_prefix_kanji: null|string}&StripeObject), payouts?: (object{debit_negative_balances: bool, schedule: (object{delay_days: int, interval: string, monthly_anchor?: int, monthly_payout_days?: int[], weekly_anchor?: string, weekly_payout_days?: string[]}&StripeObject), statement_descriptor: null|string}&StripeObject), sepa_debit_payments?: (object{creditor_id?: string}&StripeObject), treasury?: (object{tos_acceptance?: (object{date: null|int, ip: null|string, user_agent?: string}&StripeObject)}&StripeObject)}&StripeObject) $settings Options for customizing how the account functions within Stripe.
 * @property null|(object{date?: null|int, ip?: null|string, service_agreement?: string, user_agent?: null|string}&StripeObject) $tos_acceptance
 * @property null|string $type The Stripe account type. Can be <code>standard</code>, <code>express</code>, <code>custom</code>, or <code>none</code>.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 45,
    'endLine' => 523,
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
      2 => 'Stripe\\ApiOperations\\Retrieve',
    ),
    'immediateConstants' => 
    array (
      'OBJECT_NAME' => 
      array (
        'declaringClassName' => 'Stripe\\Account',
        'implementingClassName' => 'Stripe\\Account',
        'name' => 'OBJECT_NAME',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'account\'',
          'attributes' => 
          array (
            'startLine' => 47,
            'endLine' => 47,
            'startTokenPos' => 27,
            'startFilePos' => 10908,
            'endTokenPos' => 27,
            'endFilePos' => 10916,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 47,
        'endLine' => 47,
        'startColumn' => 5,
        'endColumn' => 34,
      ),
      'BUSINESS_TYPE_COMPANY' => 
      array (
        'declaringClassName' => 'Stripe\\Account',
        'implementingClassName' => 'Stripe\\Account',
        'name' => 'BUSINESS_TYPE_COMPANY',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'company\'',
          'attributes' => 
          array (
            'startLine' => 52,
            'endLine' => 52,
            'startTokenPos' => 46,
            'startFilePos' => 11023,
            'endTokenPos' => 46,
            'endFilePos' => 11031,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 52,
        'endLine' => 52,
        'startColumn' => 5,
        'endColumn' => 44,
      ),
      'BUSINESS_TYPE_GOVERNMENT_ENTITY' => 
      array (
        'declaringClassName' => 'Stripe\\Account',
        'implementingClassName' => 'Stripe\\Account',
        'name' => 'BUSINESS_TYPE_GOVERNMENT_ENTITY',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'government_entity\'',
          'attributes' => 
          array (
            'startLine' => 53,
            'endLine' => 53,
            'startTokenPos' => 55,
            'startFilePos' => 11078,
            'endTokenPos' => 55,
            'endFilePos' => 11096,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 53,
        'endLine' => 53,
        'startColumn' => 5,
        'endColumn' => 64,
      ),
      'BUSINESS_TYPE_INDIVIDUAL' => 
      array (
        'declaringClassName' => 'Stripe\\Account',
        'implementingClassName' => 'Stripe\\Account',
        'name' => 'BUSINESS_TYPE_INDIVIDUAL',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'individual\'',
          'attributes' => 
          array (
            'startLine' => 54,
            'endLine' => 54,
            'startTokenPos' => 64,
            'startFilePos' => 11136,
            'endTokenPos' => 64,
            'endFilePos' => 11147,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 54,
        'endLine' => 54,
        'startColumn' => 5,
        'endColumn' => 50,
      ),
      'BUSINESS_TYPE_NON_PROFIT' => 
      array (
        'declaringClassName' => 'Stripe\\Account',
        'implementingClassName' => 'Stripe\\Account',
        'name' => 'BUSINESS_TYPE_NON_PROFIT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'non_profit\'',
          'attributes' => 
          array (
            'startLine' => 55,
            'endLine' => 55,
            'startTokenPos' => 73,
            'startFilePos' => 11187,
            'endTokenPos' => 73,
            'endFilePos' => 11198,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 55,
        'endLine' => 55,
        'startColumn' => 5,
        'endColumn' => 50,
      ),
      'TYPE_CUSTOM' => 
      array (
        'declaringClassName' => 'Stripe\\Account',
        'implementingClassName' => 'Stripe\\Account',
        'name' => 'TYPE_CUSTOM',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'custom\'',
          'attributes' => 
          array (
            'startLine' => 57,
            'endLine' => 57,
            'startTokenPos' => 82,
            'startFilePos' => 11226,
            'endTokenPos' => 82,
            'endFilePos' => 11233,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 57,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TYPE_EXPRESS' => 
      array (
        'declaringClassName' => 'Stripe\\Account',
        'implementingClassName' => 'Stripe\\Account',
        'name' => 'TYPE_EXPRESS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'express\'',
          'attributes' => 
          array (
            'startLine' => 58,
            'endLine' => 58,
            'startTokenPos' => 91,
            'startFilePos' => 11261,
            'endTokenPos' => 91,
            'endFilePos' => 11269,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 58,
        'endLine' => 58,
        'startColumn' => 5,
        'endColumn' => 35,
      ),
      'TYPE_NONE' => 
      array (
        'declaringClassName' => 'Stripe\\Account',
        'implementingClassName' => 'Stripe\\Account',
        'name' => 'TYPE_NONE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'none\'',
          'attributes' => 
          array (
            'startLine' => 59,
            'endLine' => 59,
            'startTokenPos' => 100,
            'startFilePos' => 11294,
            'endTokenPos' => 100,
            'endFilePos' => 11299,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 59,
        'endLine' => 59,
        'startColumn' => 5,
        'endColumn' => 29,
      ),
      'TYPE_STANDARD' => 
      array (
        'declaringClassName' => 'Stripe\\Account',
        'implementingClassName' => 'Stripe\\Account',
        'name' => 'TYPE_STANDARD',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'standard\'',
          'attributes' => 
          array (
            'startLine' => 60,
            'endLine' => 60,
            'startTokenPos' => 109,
            'startFilePos' => 11328,
            'endTokenPos' => 109,
            'endFilePos' => 11337,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 60,
        'endLine' => 60,
        'startColumn' => 5,
        'endColumn' => 37,
      ),
      'PATH_CAPABILITIES' => 
      array (
        'declaringClassName' => 'Stripe\\Account',
        'implementingClassName' => 'Stripe\\Account',
        'name' => 'PATH_CAPABILITIES',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'/capabilities\'',
          'attributes' => 
          array (
            'startLine' => 315,
            'endLine' => 315,
            'startTokenPos' => 1279,
            'startFilePos' => 35469,
            'endTokenPos' => 1279,
            'endFilePos' => 35483,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 315,
        'endLine' => 315,
        'startColumn' => 5,
        'endColumn' => 46,
      ),
      'PATH_EXTERNAL_ACCOUNTS' => 
      array (
        'declaringClassName' => 'Stripe\\Account',
        'implementingClassName' => 'Stripe\\Account',
        'name' => 'PATH_EXTERNAL_ACCOUNTS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'/external_accounts\'',
          'attributes' => 
          array (
            'startLine' => 360,
            'endLine' => 360,
            'startTokenPos' => 1456,
            'startFilePos' => 37129,
            'endTokenPos' => 1456,
            'endFilePos' => 37148,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 360,
        'endLine' => 360,
        'startColumn' => 5,
        'endColumn' => 56,
      ),
      'PATH_LOGIN_LINKS' => 
      array (
        'declaringClassName' => 'Stripe\\Account',
        'implementingClassName' => 'Stripe\\Account',
        'name' => 'PATH_LOGIN_LINKS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'/login_links\'',
          'attributes' => 
          array (
            'startLine' => 434,
            'endLine' => 434,
            'startTokenPos' => 1743,
            'startFilePos' => 39996,
            'endTokenPos' => 1743,
            'endFilePos' => 40009,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 434,
        'endLine' => 434,
        'startColumn' => 5,
        'endColumn' => 44,
      ),
      'PATH_PERSONS' => 
      array (
        'declaringClassName' => 'Stripe\\Account',
        'implementingClassName' => 'Stripe\\Account',
        'name' => 'PATH_PERSONS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'/persons\'',
          'attributes' => 
          array (
            'startLine' => 449,
            'endLine' => 449,
            'startTokenPos' => 1804,
            'startFilePos' => 40490,
            'endTokenPos' => 1804,
            'endFilePos' => 40499,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 449,
        'endLine' => 449,
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
                'startLine' => 81,
                'endLine' => 81,
                'startTokenPos' => 126,
                'startFilePos' => 19826,
                'endTokenPos' => 126,
                'endFilePos' => 19829,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 81,
            'endLine' => 81,
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
                'startLine' => 81,
                'endLine' => 81,
                'startTokenPos' => 133,
                'startFilePos' => 19843,
                'endTokenPos' => 133,
                'endFilePos' => 19846,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 81,
            'endLine' => 81,
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
 * With <a href="/docs/connect">Connect</a>, you can create Stripe accounts for
 * your users. To do this, you’ll first need to <a
 * href="https://dashboard.stripe.com/account/applications/settings">register your
 * platform</a>.
 *
 * If you’ve already collected information for your connected accounts, you <a
 * href="/docs/connect/best-practices#onboarding">can prefill that information</a>
 * when creating the account. Connect Onboarding won’t ask for the prefilled
 * information during account onboarding. You can prefill any information on the
 * account.
 *
 * @param null|array{account_token?: string, business_profile?: array{annual_revenue?: array{amount: int, currency: string, fiscal_year_end: string}, estimated_worker_count?: int, mcc?: string, minority_owned_business_designation?: string[], monthly_estimated_revenue?: array{amount: int, currency: string}, name?: string, product_description?: string, support_address?: array{city?: string, country?: string, line1?: string, line2?: string, postal_code?: string, state?: string}, support_email?: string, support_phone?: string, support_url?: null|string, url?: string}, business_type?: string, capabilities?: array{acss_debit_payments?: array{requested?: bool}, affirm_payments?: array{requested?: bool}, afterpay_clearpay_payments?: array{requested?: bool}, alma_payments?: array{requested?: bool}, amazon_pay_payments?: array{requested?: bool}, au_becs_debit_payments?: array{requested?: bool}, bacs_debit_payments?: array{requested?: bool}, bancontact_payments?: array{requested?: bool}, bank_transfer_payments?: array{requested?: bool}, billie_payments?: array{requested?: bool}, blik_payments?: array{requested?: bool}, boleto_payments?: array{requested?: bool}, card_issuing?: array{requested?: bool}, card_payments?: array{requested?: bool}, cartes_bancaires_payments?: array{requested?: bool}, cashapp_payments?: array{requested?: bool}, crypto_payments?: array{requested?: bool}, eps_payments?: array{requested?: bool}, fpx_payments?: array{requested?: bool}, gb_bank_transfer_payments?: array{requested?: bool}, giropay_payments?: array{requested?: bool}, grabpay_payments?: array{requested?: bool}, ideal_payments?: array{requested?: bool}, india_international_payments?: array{requested?: bool}, jcb_payments?: array{requested?: bool}, jp_bank_transfer_payments?: array{requested?: bool}, kakao_pay_payments?: array{requested?: bool}, klarna_payments?: array{requested?: bool}, konbini_payments?: array{requested?: bool}, kr_card_payments?: array{requested?: bool}, legacy_payments?: array{requested?: bool}, link_payments?: array{requested?: bool}, mobilepay_payments?: array{requested?: bool}, multibanco_payments?: array{requested?: bool}, mx_bank_transfer_payments?: array{requested?: bool}, naver_pay_payments?: array{requested?: bool}, nz_bank_account_becs_debit_payments?: array{requested?: bool}, oxxo_payments?: array{requested?: bool}, p24_payments?: array{requested?: bool}, pay_by_bank_payments?: array{requested?: bool}, payco_payments?: array{requested?: bool}, paynow_payments?: array{requested?: bool}, pix_payments?: array{requested?: bool}, promptpay_payments?: array{requested?: bool}, revolut_pay_payments?: array{requested?: bool}, samsung_pay_payments?: array{requested?: bool}, satispay_payments?: array{requested?: bool}, sepa_bank_transfer_payments?: array{requested?: bool}, sepa_debit_payments?: array{requested?: bool}, sofort_payments?: array{requested?: bool}, swish_payments?: array{requested?: bool}, tax_reporting_us_1099_k?: array{requested?: bool}, tax_reporting_us_1099_misc?: array{requested?: bool}, transfers?: array{requested?: bool}, treasury?: array{requested?: bool}, twint_payments?: array{requested?: bool}, us_bank_account_ach_payments?: array{requested?: bool}, us_bank_transfer_payments?: array{requested?: bool}, zip_payments?: array{requested?: bool}}, company?: array{address?: array{city?: string, country?: string, line1?: string, line2?: string, postal_code?: string, state?: string}, address_kana?: array{city?: string, country?: string, line1?: string, line2?: string, postal_code?: string, state?: string, town?: string}, address_kanji?: array{city?: string, country?: string, line1?: string, line2?: string, postal_code?: string, state?: string, town?: string}, directors_provided?: bool, directorship_declaration?: array{date?: int, ip?: string, user_agent?: string}, executives_provided?: bool, export_license_id?: string, export_purpose_code?: string, name?: string, name_kana?: string, name_kanji?: string, owners_provided?: bool, ownership_declaration?: array{date?: int, ip?: string, user_agent?: string}, ownership_exemption_reason?: null|string, phone?: string, registration_date?: null|array{day: int, month: int, year: int}, registration_number?: string, structure?: null|string, tax_id?: string, tax_id_registrar?: string, vat_id?: string, verification?: array{document?: array{back?: string, front?: string}}}, controller?: array{fees?: array{payer?: string}, losses?: array{payments?: string}, requirement_collection?: string, stripe_dashboard?: array{type?: string}}, country?: string, default_currency?: string, documents?: array{bank_account_ownership_verification?: array{files?: string[]}, company_license?: array{files?: string[]}, company_memorandum_of_association?: array{files?: string[]}, company_ministerial_decree?: array{files?: string[]}, company_registration_verification?: array{files?: string[]}, company_tax_id_verification?: array{files?: string[]}, proof_of_address?: array{files?: string[]}, proof_of_registration?: array{files?: string[]}, proof_of_ultimate_beneficial_ownership?: array{files?: string[]}}, email?: string, expand?: string[], external_account?: array|string, groups?: array{payments_pricing?: null|string}, individual?: array{address?: array{city?: string, country?: string, line1?: string, line2?: string, postal_code?: string, state?: string}, address_kana?: array{city?: string, country?: string, line1?: string, line2?: string, postal_code?: string, state?: string, town?: string}, address_kanji?: array{city?: string, country?: string, line1?: string, line2?: string, postal_code?: string, state?: string, town?: string}, dob?: null|array{day: int, month: int, year: int}, email?: string, first_name?: string, first_name_kana?: string, first_name_kanji?: string, full_name_aliases?: null|string[], gender?: string, id_number?: string, id_number_secondary?: string, last_name?: string, last_name_kana?: string, last_name_kanji?: string, maiden_name?: string, metadata?: null|array<string, string>, phone?: string, political_exposure?: string, registered_address?: array{city?: string, country?: string, line1?: string, line2?: string, postal_code?: string, state?: string}, relationship?: array{director?: bool, executive?: bool, owner?: bool, percent_ownership?: null|float, title?: string}, ssn_last_4?: string, verification?: array{additional_document?: array{back?: string, front?: string}, document?: array{back?: string, front?: string}}}, metadata?: null|array<string, string>, settings?: array{bacs_debit_payments?: array{display_name?: string}, branding?: array{icon?: string, logo?: string, primary_color?: string, secondary_color?: string}, card_issuing?: array{tos_acceptance?: array{date?: int, ip?: string, user_agent?: null|string}}, card_payments?: array{decline_on?: array{avs_failure?: bool, cvc_failure?: bool}, statement_descriptor_prefix?: string, statement_descriptor_prefix_kana?: null|string, statement_descriptor_prefix_kanji?: null|string}, invoices?: array{hosted_payment_method_save?: string}, payments?: array{statement_descriptor?: string, statement_descriptor_kana?: string, statement_descriptor_kanji?: string}, payouts?: array{debit_negative_balances?: bool, schedule?: array{delay_days?: array|int|string, interval?: string, monthly_anchor?: int, monthly_payout_days?: int[], weekly_anchor?: string, weekly_payout_days?: string[]}, statement_descriptor?: string}, treasury?: array{tos_acceptance?: array{date?: int, ip?: string, user_agent?: null|string}}}, tos_acceptance?: array{date?: int, ip?: string, service_agreement?: string, user_agent?: string}, type?: string} $params
 * @param null|array|string $options
 *
 * @return Account the created resource
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 81,
        'endLine' => 91,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Account',
        'implementingClassName' => 'Stripe\\Account',
        'currentClassName' => 'Stripe\\Account',
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
                'startLine' => 114,
                'endLine' => 114,
                'startTokenPos' => 228,
                'startFilePos' => 21074,
                'endTokenPos' => 228,
                'endFilePos' => 21077,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 114,
            'endLine' => 114,
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
                'startLine' => 114,
                'endLine' => 114,
                'startTokenPos' => 235,
                'startFilePos' => 21088,
                'endTokenPos' => 235,
                'endFilePos' => 21091,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 114,
            'endLine' => 114,
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
 * With <a href="/connect">Connect</a>, you can delete accounts you manage.
 *
 * Test-mode accounts can be deleted at any time.
 *
 * Live-mode accounts that have access to the standard dashboard and Stripe is
 * responsible for negative account balances cannot be deleted, which includes
 * Standard accounts. All other Live-mode accounts, can be deleted when all <a
 * href="/api/balance/balance_object">balances</a> are zero.
 *
 * If you want to delete your own account, use the <a
 * href="https://dashboard.stripe.com/settings/account">account information tab in
 * your account settings</a> instead.
 *
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return Account the deleted resource
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 114,
        'endLine' => 123,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Account',
        'implementingClassName' => 'Stripe\\Account',
        'currentClassName' => 'Stripe\\Account',
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
                'startLine' => 136,
                'endLine' => 136,
                'startTokenPos' => 318,
                'startFilePos' => 21858,
                'endTokenPos' => 318,
                'endFilePos' => 21861,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 136,
            'endLine' => 136,
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
                'startLine' => 136,
                'endLine' => 136,
                'startTokenPos' => 325,
                'startFilePos' => 21872,
                'endTokenPos' => 325,
                'endFilePos' => 21875,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 136,
            'endLine' => 136,
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
 * Returns a list of accounts connected to your platform via <a
 * href="/docs/connect">Connect</a>. If you’re not a platform, the list is empty.
 *
 * @param null|array{created?: array|int, ending_before?: string, expand?: string[], limit?: int, starting_after?: string} $params
 * @param null|array|string $opts
 *
 * @return Collection<Account> of ApiResources
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 136,
        'endLine' => 141,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Account',
        'implementingClassName' => 'Stripe\\Account',
        'currentClassName' => 'Stripe\\Account',
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
            'startLine' => 172,
            'endLine' => 172,
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
                'startLine' => 172,
                'endLine' => 172,
                'startTokenPos' => 381,
                'startFilePos' => 30995,
                'endTokenPos' => 381,
                'endFilePos' => 30998,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 172,
            'endLine' => 172,
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
                'startLine' => 172,
                'endLine' => 172,
                'startTokenPos' => 388,
                'startFilePos' => 31009,
                'endTokenPos' => 388,
                'endFilePos' => 31012,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 172,
            'endLine' => 172,
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
 * Updates a <a href="/connect/accounts">connected account</a> by setting the
 * values of the parameters passed. Any parameters not provided are left unchanged.
 *
 * For accounts where <a
 * href="/api/accounts/object#account_object-controller-requirement_collection">controller.requirement_collection</a>
 * is <code>application</code>, which includes Custom accounts, you can update any
 * information on the account.
 *
 * For accounts where <a
 * href="/api/accounts/object#account_object-controller-requirement_collection">controller.requirement_collection</a>
 * is <code>stripe</code>, which includes Standard and Express accounts, you can
 * update all information until you create an <a href="/api/account_links">Account
 * Link</a> or <a href="/api/account_sessions">Account Session</a> to start Connect
 * onboarding, after which some properties can no longer be updated.
 *
 * To update your own account, use the <a
 * href="https://dashboard.stripe.com/settings/account">Dashboard</a>. Refer to our
 * <a href="/docs/connect/updating-accounts">Connect</a> documentation to learn
 * more about updating accounts.
 *
 * @param string $id the ID of the resource to update
 * @param null|array{account_token?: string, business_profile?: array{annual_revenue?: array{amount: int, currency: string, fiscal_year_end: string}, estimated_worker_count?: int, mcc?: string, minority_owned_business_designation?: string[], monthly_estimated_revenue?: array{amount: int, currency: string}, name?: string, product_description?: string, support_address?: array{city?: string, country?: string, line1?: string, line2?: string, postal_code?: string, state?: string}, support_email?: string, support_phone?: string, support_url?: null|string, url?: string}, business_type?: string, capabilities?: array{acss_debit_payments?: array{requested?: bool}, affirm_payments?: array{requested?: bool}, afterpay_clearpay_payments?: array{requested?: bool}, alma_payments?: array{requested?: bool}, amazon_pay_payments?: array{requested?: bool}, au_becs_debit_payments?: array{requested?: bool}, bacs_debit_payments?: array{requested?: bool}, bancontact_payments?: array{requested?: bool}, bank_transfer_payments?: array{requested?: bool}, billie_payments?: array{requested?: bool}, blik_payments?: array{requested?: bool}, boleto_payments?: array{requested?: bool}, card_issuing?: array{requested?: bool}, card_payments?: array{requested?: bool}, cartes_bancaires_payments?: array{requested?: bool}, cashapp_payments?: array{requested?: bool}, crypto_payments?: array{requested?: bool}, eps_payments?: array{requested?: bool}, fpx_payments?: array{requested?: bool}, gb_bank_transfer_payments?: array{requested?: bool}, giropay_payments?: array{requested?: bool}, grabpay_payments?: array{requested?: bool}, ideal_payments?: array{requested?: bool}, india_international_payments?: array{requested?: bool}, jcb_payments?: array{requested?: bool}, jp_bank_transfer_payments?: array{requested?: bool}, kakao_pay_payments?: array{requested?: bool}, klarna_payments?: array{requested?: bool}, konbini_payments?: array{requested?: bool}, kr_card_payments?: array{requested?: bool}, legacy_payments?: array{requested?: bool}, link_payments?: array{requested?: bool}, mobilepay_payments?: array{requested?: bool}, multibanco_payments?: array{requested?: bool}, mx_bank_transfer_payments?: array{requested?: bool}, naver_pay_payments?: array{requested?: bool}, nz_bank_account_becs_debit_payments?: array{requested?: bool}, oxxo_payments?: array{requested?: bool}, p24_payments?: array{requested?: bool}, pay_by_bank_payments?: array{requested?: bool}, payco_payments?: array{requested?: bool}, paynow_payments?: array{requested?: bool}, pix_payments?: array{requested?: bool}, promptpay_payments?: array{requested?: bool}, revolut_pay_payments?: array{requested?: bool}, samsung_pay_payments?: array{requested?: bool}, satispay_payments?: array{requested?: bool}, sepa_bank_transfer_payments?: array{requested?: bool}, sepa_debit_payments?: array{requested?: bool}, sofort_payments?: array{requested?: bool}, swish_payments?: array{requested?: bool}, tax_reporting_us_1099_k?: array{requested?: bool}, tax_reporting_us_1099_misc?: array{requested?: bool}, transfers?: array{requested?: bool}, treasury?: array{requested?: bool}, twint_payments?: array{requested?: bool}, us_bank_account_ach_payments?: array{requested?: bool}, us_bank_transfer_payments?: array{requested?: bool}, zip_payments?: array{requested?: bool}}, company?: array{address?: array{city?: string, country?: string, line1?: string, line2?: string, postal_code?: string, state?: string}, address_kana?: array{city?: string, country?: string, line1?: string, line2?: string, postal_code?: string, state?: string, town?: string}, address_kanji?: array{city?: string, country?: string, line1?: string, line2?: string, postal_code?: string, state?: string, town?: string}, directors_provided?: bool, directorship_declaration?: array{date?: int, ip?: string, user_agent?: string}, executives_provided?: bool, export_license_id?: string, export_purpose_code?: string, name?: string, name_kana?: string, name_kanji?: string, owners_provided?: bool, ownership_declaration?: array{date?: int, ip?: string, user_agent?: string}, ownership_exemption_reason?: null|string, phone?: string, registration_date?: null|array{day: int, month: int, year: int}, registration_number?: string, structure?: null|string, tax_id?: string, tax_id_registrar?: string, vat_id?: string, verification?: array{document?: array{back?: string, front?: string}}}, default_currency?: string, documents?: array{bank_account_ownership_verification?: array{files?: string[]}, company_license?: array{files?: string[]}, company_memorandum_of_association?: array{files?: string[]}, company_ministerial_decree?: array{files?: string[]}, company_registration_verification?: array{files?: string[]}, company_tax_id_verification?: array{files?: string[]}, proof_of_address?: array{files?: string[]}, proof_of_registration?: array{files?: string[]}, proof_of_ultimate_beneficial_ownership?: array{files?: string[]}}, email?: string, expand?: string[], external_account?: null|array|string, groups?: array{payments_pricing?: null|string}, individual?: array{address?: array{city?: string, country?: string, line1?: string, line2?: string, postal_code?: string, state?: string}, address_kana?: array{city?: string, country?: string, line1?: string, line2?: string, postal_code?: string, state?: string, town?: string}, address_kanji?: array{city?: string, country?: string, line1?: string, line2?: string, postal_code?: string, state?: string, town?: string}, dob?: null|array{day: int, month: int, year: int}, email?: string, first_name?: string, first_name_kana?: string, first_name_kanji?: string, full_name_aliases?: null|string[], gender?: string, id_number?: string, id_number_secondary?: string, last_name?: string, last_name_kana?: string, last_name_kanji?: string, maiden_name?: string, metadata?: null|array<string, string>, phone?: string, political_exposure?: string, registered_address?: array{city?: string, country?: string, line1?: string, line2?: string, postal_code?: string, state?: string}, relationship?: array{director?: bool, executive?: bool, owner?: bool, percent_ownership?: null|float, title?: string}, ssn_last_4?: string, verification?: array{additional_document?: array{back?: string, front?: string}, document?: array{back?: string, front?: string}}}, metadata?: null|array<string, string>, settings?: array{bacs_debit_payments?: array{display_name?: string}, branding?: array{icon?: string, logo?: string, primary_color?: string, secondary_color?: string}, card_issuing?: array{tos_acceptance?: array{date?: int, ip?: string, user_agent?: null|string}}, card_payments?: array{decline_on?: array{avs_failure?: bool, cvc_failure?: bool}, statement_descriptor_prefix?: string, statement_descriptor_prefix_kana?: null|string, statement_descriptor_prefix_kanji?: null|string}, invoices?: array{default_account_tax_ids?: null|string[], hosted_payment_method_save?: string}, payments?: array{statement_descriptor?: string, statement_descriptor_kana?: string, statement_descriptor_kanji?: string}, payouts?: array{debit_negative_balances?: bool, schedule?: array{delay_days?: array|int|string, interval?: string, monthly_anchor?: int, monthly_payout_days?: int[], weekly_anchor?: string, weekly_payout_days?: string[]}, statement_descriptor?: string}, treasury?: array{tos_acceptance?: array{date?: int, ip?: string, user_agent?: null|string}}}, tos_acceptance?: array{date?: int, ip?: string, service_agreement?: string, user_agent?: string}} $params
 * @param null|array|string $opts
 *
 * @return Account the updated resource
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 172,
        'endLine' => 182,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Account',
        'implementingClassName' => 'Stripe\\Account',
        'currentClassName' => 'Stripe\\Account',
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
        'startLine' => 188,
        'endLine' => 199,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Account',
        'implementingClassName' => 'Stripe\\Account',
        'currentClassName' => 'Stripe\\Account',
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
        'docComment' => NULL,
        'startLine' => 201,
        'endLine' => 208,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Account',
        'implementingClassName' => 'Stripe\\Account',
        'currentClassName' => 'Stripe\\Account',
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
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 219,
                'endLine' => 219,
                'startTokenPos' => 608,
                'startFilePos' => 32244,
                'endTokenPos' => 608,
                'endFilePos' => 32247,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 219,
            'endLine' => 219,
            'startColumn' => 37,
            'endColumn' => 46,
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
                'startLine' => 219,
                'endLine' => 219,
                'startTokenPos' => 615,
                'startFilePos' => 32258,
                'endTokenPos' => 615,
                'endFilePos' => 32261,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 219,
            'endLine' => 219,
            'startColumn' => 49,
            'endColumn' => 60,
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
 * @param null|array|string $id the ID of the account to retrieve, or an
 *     options array containing an `id` key
 * @param null|array|string $opts
 *
 * @return Account
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 219,
        'endLine' => 227,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Account',
        'implementingClassName' => 'Stripe\\Account',
        'currentClassName' => 'Stripe\\Account',
        'aliasName' => NULL,
      ),
      'serializeParameters' => 
      array (
        'name' => 'serializeParameters',
        'parameters' => 
        array (
          'force' => 
          array (
            'name' => 'force',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 229,
                'endLine' => 229,
                'startTokenPos' => 694,
                'startFilePos' => 32503,
                'endTokenPos' => 694,
                'endFilePos' => 32507,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 229,
            'endLine' => 229,
            'startColumn' => 41,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 229,
        'endLine' => 249,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Account',
        'implementingClassName' => 'Stripe\\Account',
        'currentClassName' => 'Stripe\\Account',
        'aliasName' => NULL,
      ),
      'serializeAdditionalOwners' => 
      array (
        'name' => 'serializeAdditionalOwners',
        'parameters' => 
        array (
          'legalEntity' => 
          array (
            'name' => 'legalEntity',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 251,
            'endLine' => 251,
            'startColumn' => 48,
            'endColumn' => 59,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'additionalOwners' => 
          array (
            'name' => 'additionalOwners',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 251,
            'endLine' => 251,
            'startColumn' => 62,
            'endColumn' => 78,
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
        'startLine' => 251,
        'endLine' => 278,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Account',
        'implementingClassName' => 'Stripe\\Account',
        'currentClassName' => 'Stripe\\Account',
        'aliasName' => NULL,
      ),
      'deauthorize' => 
      array (
        'name' => 'deauthorize',
        'parameters' => 
        array (
          'clientId' => 
          array (
            'name' => 'clientId',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 288,
                'endLine' => 288,
                'startTokenPos' => 1137,
                'startFilePos' => 34765,
                'endTokenPos' => 1137,
                'endFilePos' => 34768,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 288,
            'endLine' => 288,
            'startColumn' => 33,
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
                'startLine' => 288,
                'endLine' => 288,
                'startTokenPos' => 1144,
                'startFilePos' => 34779,
                'endTokenPos' => 1144,
                'endFilePos' => 34782,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 288,
            'endLine' => 288,
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
 * @param null|array $clientId
 * @param null|array|string $opts
 *
 * @return StripeObject object containing the response from the API
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 288,
        'endLine' => 296,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Account',
        'implementingClassName' => 'Stripe\\Account',
        'currentClassName' => 'Stripe\\Account',
        'aliasName' => NULL,
      ),
      'reject' => 
      array (
        'name' => 'reject',
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
                'startLine' => 306,
                'endLine' => 306,
                'startTokenPos' => 1201,
                'startFilePos' => 35208,
                'endTokenPos' => 1201,
                'endFilePos' => 35211,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 306,
            'endLine' => 306,
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
                'startLine' => 306,
                'endLine' => 306,
                'startTokenPos' => 1208,
                'startFilePos' => 35222,
                'endTokenPos' => 1208,
                'endFilePos' => 35225,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 306,
            'endLine' => 306,
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
 * @return Account the rejected account
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 306,
        'endLine' => 313,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Account',
        'implementingClassName' => 'Stripe\\Account',
        'currentClassName' => 'Stripe\\Account',
        'aliasName' => NULL,
      ),
      'allCapabilities' => 
      array (
        'name' => 'allCapabilities',
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
            'startLine' => 326,
            'endLine' => 326,
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
                'startLine' => 326,
                'endLine' => 326,
                'startTokenPos' => 1299,
                'startFilePos' => 35858,
                'endTokenPos' => 1299,
                'endFilePos' => 35861,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 326,
            'endLine' => 326,
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
                'startLine' => 326,
                'endLine' => 326,
                'startTokenPos' => 1306,
                'startFilePos' => 35872,
                'endTokenPos' => 1306,
                'endFilePos' => 35875,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 326,
            'endLine' => 326,
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
 * @param string $id the ID of the account on which to retrieve the capabilities
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return Collection<Capability> the list of capabilities
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 326,
        'endLine' => 329,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Account',
        'implementingClassName' => 'Stripe\\Account',
        'currentClassName' => 'Stripe\\Account',
        'aliasName' => NULL,
      ),
      'retrieveCapability' => 
      array (
        'name' => 'retrieveCapability',
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
            'startLine' => 341,
            'endLine' => 341,
            'startColumn' => 47,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'capabilityId' => 
          array (
            'name' => 'capabilityId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 341,
            'endLine' => 341,
            'startColumn' => 52,
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
                'startLine' => 341,
                'endLine' => 341,
                'startTokenPos' => 1354,
                'startFilePos' => 36399,
                'endTokenPos' => 1354,
                'endFilePos' => 36402,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 341,
            'endLine' => 341,
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
                'startLine' => 341,
                'endLine' => 341,
                'startTokenPos' => 1361,
                'startFilePos' => 36413,
                'endTokenPos' => 1361,
                'endFilePos' => 36416,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 341,
            'endLine' => 341,
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
 * @param string $id the ID of the account to which the capability belongs
 * @param string $capabilityId the ID of the capability to retrieve
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return Capability
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 341,
        'endLine' => 344,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Account',
        'implementingClassName' => 'Stripe\\Account',
        'currentClassName' => 'Stripe\\Account',
        'aliasName' => NULL,
      ),
      'updateCapability' => 
      array (
        'name' => 'updateCapability',
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
            'startLine' => 356,
            'endLine' => 356,
            'startColumn' => 45,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'capabilityId' => 
          array (
            'name' => 'capabilityId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 356,
            'endLine' => 356,
            'startColumn' => 50,
            'endColumn' => 62,
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
                'startLine' => 356,
                'endLine' => 356,
                'startTokenPos' => 1412,
                'startFilePos' => 36955,
                'endTokenPos' => 1412,
                'endFilePos' => 36958,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 356,
            'endLine' => 356,
            'startColumn' => 65,
            'endColumn' => 78,
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
                'startLine' => 356,
                'endLine' => 356,
                'startTokenPos' => 1419,
                'startFilePos' => 36969,
                'endTokenPos' => 1419,
                'endFilePos' => 36972,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 356,
            'endLine' => 356,
            'startColumn' => 81,
            'endColumn' => 92,
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
 * @param string $id the ID of the account to which the capability belongs
 * @param string $capabilityId the ID of the capability to update
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return Capability
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 356,
        'endLine' => 359,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Account',
        'implementingClassName' => 'Stripe\\Account',
        'currentClassName' => 'Stripe\\Account',
        'aliasName' => NULL,
      ),
      'allExternalAccounts' => 
      array (
        'name' => 'allExternalAccounts',
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
            'startLine' => 371,
            'endLine' => 371,
            'startColumn' => 48,
            'endColumn' => 50,
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
                'startLine' => 371,
                'endLine' => 371,
                'startTokenPos' => 1476,
                'startFilePos' => 37565,
                'endTokenPos' => 1476,
                'endFilePos' => 37568,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 371,
            'endLine' => 371,
            'startColumn' => 53,
            'endColumn' => 66,
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
                'startLine' => 371,
                'endLine' => 371,
                'startTokenPos' => 1483,
                'startFilePos' => 37579,
                'endTokenPos' => 1483,
                'endFilePos' => 37582,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 371,
            'endLine' => 371,
            'startColumn' => 69,
            'endColumn' => 80,
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
 * @param string $id the ID of the account on which to retrieve the external accounts
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return Collection<BankAccount|Card> the list of external accounts (BankAccount or Card)
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 371,
        'endLine' => 374,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Account',
        'implementingClassName' => 'Stripe\\Account',
        'currentClassName' => 'Stripe\\Account',
        'aliasName' => NULL,
      ),
      'createExternalAccount' => 
      array (
        'name' => 'createExternalAccount',
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
            'startLine' => 385,
            'endLine' => 385,
            'startColumn' => 50,
            'endColumn' => 52,
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
                'startLine' => 385,
                'endLine' => 385,
                'startTokenPos' => 1528,
                'startFilePos' => 38041,
                'endTokenPos' => 1528,
                'endFilePos' => 38044,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 385,
            'endLine' => 385,
            'startColumn' => 55,
            'endColumn' => 68,
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
                'startLine' => 385,
                'endLine' => 385,
                'startTokenPos' => 1535,
                'startFilePos' => 38055,
                'endTokenPos' => 1535,
                'endFilePos' => 38058,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 385,
            'endLine' => 385,
            'startColumn' => 71,
            'endColumn' => 82,
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
 * @param string $id the ID of the account on which to create the external account
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return BankAccount|Card
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 385,
        'endLine' => 388,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Account',
        'implementingClassName' => 'Stripe\\Account',
        'currentClassName' => 'Stripe\\Account',
        'aliasName' => NULL,
      ),
      'deleteExternalAccount' => 
      array (
        'name' => 'deleteExternalAccount',
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
            'startLine' => 400,
            'endLine' => 400,
            'startColumn' => 50,
            'endColumn' => 52,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'externalAccountId' => 
          array (
            'name' => 'externalAccountId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 400,
            'endLine' => 400,
            'startColumn' => 55,
            'endColumn' => 72,
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
                'startLine' => 400,
                'endLine' => 400,
                'startTokenPos' => 1583,
                'startFilePos' => 38618,
                'endTokenPos' => 1583,
                'endFilePos' => 38621,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 400,
            'endLine' => 400,
            'startColumn' => 75,
            'endColumn' => 88,
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
                'startLine' => 400,
                'endLine' => 400,
                'startTokenPos' => 1590,
                'startFilePos' => 38632,
                'endTokenPos' => 1590,
                'endFilePos' => 38635,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 400,
            'endLine' => 400,
            'startColumn' => 91,
            'endColumn' => 102,
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
 * @param string $id the ID of the account to which the external account belongs
 * @param string $externalAccountId the ID of the external account to delete
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return BankAccount|Card
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 400,
        'endLine' => 403,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Account',
        'implementingClassName' => 'Stripe\\Account',
        'currentClassName' => 'Stripe\\Account',
        'aliasName' => NULL,
      ),
      'retrieveExternalAccount' => 
      array (
        'name' => 'retrieveExternalAccount',
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
            'startLine' => 415,
            'endLine' => 415,
            'startColumn' => 52,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'externalAccountId' => 
          array (
            'name' => 'externalAccountId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 415,
            'endLine' => 415,
            'startColumn' => 57,
            'endColumn' => 74,
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
                'startLine' => 415,
                'endLine' => 415,
                'startTokenPos' => 1641,
                'startFilePos' => 39219,
                'endTokenPos' => 1641,
                'endFilePos' => 39222,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 415,
            'endLine' => 415,
            'startColumn' => 77,
            'endColumn' => 90,
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
                'startLine' => 415,
                'endLine' => 415,
                'startTokenPos' => 1648,
                'startFilePos' => 39233,
                'endTokenPos' => 1648,
                'endFilePos' => 39236,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 415,
            'endLine' => 415,
            'startColumn' => 93,
            'endColumn' => 104,
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
 * @param string $id the ID of the account to which the external account belongs
 * @param string $externalAccountId the ID of the external account to retrieve
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return BankAccount|Card
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 415,
        'endLine' => 418,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Account',
        'implementingClassName' => 'Stripe\\Account',
        'currentClassName' => 'Stripe\\Account',
        'aliasName' => NULL,
      ),
      'updateExternalAccount' => 
      array (
        'name' => 'updateExternalAccount',
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
            'startLine' => 430,
            'endLine' => 430,
            'startColumn' => 50,
            'endColumn' => 52,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'externalAccountId' => 
          array (
            'name' => 'externalAccountId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 430,
            'endLine' => 430,
            'startColumn' => 55,
            'endColumn' => 72,
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
                'startLine' => 430,
                'endLine' => 430,
                'startTokenPos' => 1699,
                'startFilePos' => 39818,
                'endTokenPos' => 1699,
                'endFilePos' => 39821,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 430,
            'endLine' => 430,
            'startColumn' => 75,
            'endColumn' => 88,
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
                'startLine' => 430,
                'endLine' => 430,
                'startTokenPos' => 1706,
                'startFilePos' => 39832,
                'endTokenPos' => 1706,
                'endFilePos' => 39835,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 430,
            'endLine' => 430,
            'startColumn' => 91,
            'endColumn' => 102,
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
 * @param string $id the ID of the account to which the external account belongs
 * @param string $externalAccountId the ID of the external account to update
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return BankAccount|Card
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 430,
        'endLine' => 433,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Account',
        'implementingClassName' => 'Stripe\\Account',
        'currentClassName' => 'Stripe\\Account',
        'aliasName' => NULL,
      ),
      'createLoginLink' => 
      array (
        'name' => 'createLoginLink',
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
            'startLine' => 445,
            'endLine' => 445,
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
                'startLine' => 445,
                'endLine' => 445,
                'startTokenPos' => 1763,
                'startFilePos' => 40342,
                'endTokenPos' => 1763,
                'endFilePos' => 40345,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 445,
            'endLine' => 445,
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
                'startLine' => 445,
                'endLine' => 445,
                'startTokenPos' => 1770,
                'startFilePos' => 40356,
                'endTokenPos' => 1770,
                'endFilePos' => 40359,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 445,
            'endLine' => 445,
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
 * @param string $id the ID of the account on which to create the login link
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return LoginLink
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 445,
        'endLine' => 448,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Account',
        'implementingClassName' => 'Stripe\\Account',
        'currentClassName' => 'Stripe\\Account',
        'aliasName' => NULL,
      ),
      'allPersons' => 
      array (
        'name' => 'allPersons',
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
            'startLine' => 460,
            'endLine' => 460,
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
                'startLine' => 460,
                'endLine' => 460,
                'startTokenPos' => 1824,
                'startFilePos' => 40855,
                'endTokenPos' => 1824,
                'endFilePos' => 40858,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 460,
            'endLine' => 460,
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
                'startLine' => 460,
                'endLine' => 460,
                'startTokenPos' => 1831,
                'startFilePos' => 40869,
                'endTokenPos' => 1831,
                'endFilePos' => 40872,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 460,
            'endLine' => 460,
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
 * @param string $id the ID of the account on which to retrieve the persons
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return Collection<Person> the list of persons
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 460,
        'endLine' => 463,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Account',
        'implementingClassName' => 'Stripe\\Account',
        'currentClassName' => 'Stripe\\Account',
        'aliasName' => NULL,
      ),
      'createPerson' => 
      array (
        'name' => 'createPerson',
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
            'startLine' => 474,
            'endLine' => 474,
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
                'startLine' => 474,
                'endLine' => 474,
                'startTokenPos' => 1876,
                'startFilePos' => 41292,
                'endTokenPos' => 1876,
                'endFilePos' => 41295,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 474,
            'endLine' => 474,
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
                'startLine' => 474,
                'endLine' => 474,
                'startTokenPos' => 1883,
                'startFilePos' => 41306,
                'endTokenPos' => 1883,
                'endFilePos' => 41309,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 474,
            'endLine' => 474,
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
 * @param string $id the ID of the account on which to create the person
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return Person
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 474,
        'endLine' => 477,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Account',
        'implementingClassName' => 'Stripe\\Account',
        'currentClassName' => 'Stripe\\Account',
        'aliasName' => NULL,
      ),
      'deletePerson' => 
      array (
        'name' => 'deletePerson',
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
            'startLine' => 489,
            'endLine' => 489,
            'startColumn' => 41,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'personId' => 
          array (
            'name' => 'personId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 489,
            'endLine' => 489,
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
                'startLine' => 489,
                'endLine' => 489,
                'startTokenPos' => 1931,
                'startFilePos' => 41802,
                'endTokenPos' => 1931,
                'endFilePos' => 41805,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 489,
            'endLine' => 489,
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
                'startLine' => 489,
                'endLine' => 489,
                'startTokenPos' => 1938,
                'startFilePos' => 41816,
                'endTokenPos' => 1938,
                'endFilePos' => 41819,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 489,
            'endLine' => 489,
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
 * @param string $id the ID of the account to which the person belongs
 * @param string $personId the ID of the person to delete
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return Person
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 489,
        'endLine' => 492,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Account',
        'implementingClassName' => 'Stripe\\Account',
        'currentClassName' => 'Stripe\\Account',
        'aliasName' => NULL,
      ),
      'retrievePerson' => 
      array (
        'name' => 'retrievePerson',
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
            'startLine' => 504,
            'endLine' => 504,
            'startColumn' => 43,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'personId' => 
          array (
            'name' => 'personId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 504,
            'endLine' => 504,
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
                'startLine' => 504,
                'endLine' => 504,
                'startTokenPos' => 1989,
                'startFilePos' => 42327,
                'endTokenPos' => 1989,
                'endFilePos' => 42330,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 504,
            'endLine' => 504,
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
                'startLine' => 504,
                'endLine' => 504,
                'startTokenPos' => 1996,
                'startFilePos' => 42341,
                'endTokenPos' => 1996,
                'endFilePos' => 42344,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 504,
            'endLine' => 504,
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
 * @param string $id the ID of the account to which the person belongs
 * @param string $personId the ID of the person to retrieve
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return Person
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 504,
        'endLine' => 507,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Account',
        'implementingClassName' => 'Stripe\\Account',
        'currentClassName' => 'Stripe\\Account',
        'aliasName' => NULL,
      ),
      'updatePerson' => 
      array (
        'name' => 'updatePerson',
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
            'startLine' => 519,
            'endLine' => 519,
            'startColumn' => 41,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'personId' => 
          array (
            'name' => 'personId',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 519,
            'endLine' => 519,
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
                'startLine' => 519,
                'endLine' => 519,
                'startTokenPos' => 2047,
                'startFilePos' => 42850,
                'endTokenPos' => 2047,
                'endFilePos' => 42853,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 519,
            'endLine' => 519,
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
                'startLine' => 519,
                'endLine' => 519,
                'startTokenPos' => 2054,
                'startFilePos' => 42864,
                'endTokenPos' => 2054,
                'endFilePos' => 42867,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 519,
            'endLine' => 519,
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
 * @param string $id the ID of the account to which the person belongs
 * @param string $personId the ID of the person to update
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return Person
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 519,
        'endLine' => 522,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Account',
        'implementingClassName' => 'Stripe\\Account',
        'currentClassName' => 'Stripe\\Account',
        'aliasName' => NULL,
      ),
    ),
    'traitsData' => 
    array (
      'aliases' => 
      array (
        'Stripe\\ApiOperations\\Retrieve' => 
        array (
          0 => 
          array (
            'alias' => '_retrieve',
            'method' => 'retrieve',
            'hash' => 'stripe\\apioperations\\retrieve::retrieve',
          ),
        ),
      ),
      'modifiers' => 
      array (
        'stripe\\apioperations\\retrieve::retrieve' => 2,
      ),
      'precedences' => 
      array (
      ),
      'hashes' => 
      array (
        'stripe\\apioperations\\retrieve::retrieve' => 'Stripe\\ApiOperations\\Retrieve::retrieve',
      ),
    ),
  ),
));