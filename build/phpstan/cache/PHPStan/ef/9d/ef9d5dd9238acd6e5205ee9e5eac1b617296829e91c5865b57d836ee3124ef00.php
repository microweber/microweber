<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/StripeClient.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Stripe\StripeClient
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-9b3e3d0e5ab143306299075b4de66e3ead5f18a28a829c84d30e3a646fbc535b-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Stripe\\StripeClient',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/StripeClient.php',
      ),
    ),
    'namespace' => 'Stripe',
    'name' => 'Stripe\\StripeClient',
    'shortName' => 'StripeClient',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Client used to send requests to Stripe\'s API.
 *
 * @property Service\\OAuthService $oauth
 * // The beginning of the section generated from our OpenAPI spec
 * @property Service\\AccountLinkService $accountLinks
 * @property Service\\AccountService $accounts
 * @property Service\\AccountSessionService $accountSessions
 * @property Service\\ApplePayDomainService $applePayDomains
 * @property Service\\ApplicationFeeService $applicationFees
 * @property Service\\Apps\\AppsServiceFactory $apps
 * @property Service\\BalanceService $balance
 * @property Service\\BalanceTransactionService $balanceTransactions
 * @property Service\\Billing\\BillingServiceFactory $billing
 * @property Service\\BillingPortal\\BillingPortalServiceFactory $billingPortal
 * @property Service\\ChargeService $charges
 * @property Service\\Checkout\\CheckoutServiceFactory $checkout
 * @property Service\\Climate\\ClimateServiceFactory $climate
 * @property Service\\ConfirmationTokenService $confirmationTokens
 * @property Service\\CountrySpecService $countrySpecs
 * @property Service\\CouponService $coupons
 * @property Service\\CreditNoteService $creditNotes
 * @property Service\\CustomerService $customers
 * @property Service\\CustomerSessionService $customerSessions
 * @property Service\\DisputeService $disputes
 * @property Service\\Entitlements\\EntitlementsServiceFactory $entitlements
 * @property Service\\EphemeralKeyService $ephemeralKeys
 * @property Service\\EventService $events
 * @property Service\\ExchangeRateService $exchangeRates
 * @property Service\\FileLinkService $fileLinks
 * @property Service\\FileService $files
 * @property Service\\FinancialConnections\\FinancialConnectionsServiceFactory $financialConnections
 * @property Service\\Forwarding\\ForwardingServiceFactory $forwarding
 * @property Service\\Identity\\IdentityServiceFactory $identity
 * @property Service\\InvoiceItemService $invoiceItems
 * @property Service\\InvoicePaymentService $invoicePayments
 * @property Service\\InvoiceRenderingTemplateService $invoiceRenderingTemplates
 * @property Service\\InvoiceService $invoices
 * @property Service\\Issuing\\IssuingServiceFactory $issuing
 * @property Service\\MandateService $mandates
 * @property Service\\PaymentIntentService $paymentIntents
 * @property Service\\PaymentLinkService $paymentLinks
 * @property Service\\PaymentMethodConfigurationService $paymentMethodConfigurations
 * @property Service\\PaymentMethodDomainService $paymentMethodDomains
 * @property Service\\PaymentMethodService $paymentMethods
 * @property Service\\PayoutService $payouts
 * @property Service\\PlanService $plans
 * @property Service\\PriceService $prices
 * @property Service\\ProductService $products
 * @property Service\\PromotionCodeService $promotionCodes
 * @property Service\\QuoteService $quotes
 * @property Service\\Radar\\RadarServiceFactory $radar
 * @property Service\\RefundService $refunds
 * @property Service\\Reporting\\ReportingServiceFactory $reporting
 * @property Service\\ReviewService $reviews
 * @property Service\\SetupAttemptService $setupAttempts
 * @property Service\\SetupIntentService $setupIntents
 * @property Service\\ShippingRateService $shippingRates
 * @property Service\\Sigma\\SigmaServiceFactory $sigma
 * @property Service\\SourceService $sources
 * @property Service\\SubscriptionItemService $subscriptionItems
 * @property Service\\SubscriptionService $subscriptions
 * @property Service\\SubscriptionScheduleService $subscriptionSchedules
 * @property Service\\Tax\\TaxServiceFactory $tax
 * @property Service\\TaxCodeService $taxCodes
 * @property Service\\TaxIdService $taxIds
 * @property Service\\TaxRateService $taxRates
 * @property Service\\Terminal\\TerminalServiceFactory $terminal
 * @property Service\\TestHelpers\\TestHelpersServiceFactory $testHelpers
 * @property Service\\TokenService $tokens
 * @property Service\\TopupService $topups
 * @property Service\\TransferService $transfers
 * @property Service\\Treasury\\TreasuryServiceFactory $treasury
 * @property Service\\V2\\V2ServiceFactory $v2
 * @property Service\\WebhookEndpointService $webhookEndpoints
 * // The end of the section generated from our OpenAPI spec
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 82,
    'endLine' => 102,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Stripe\\BaseStripeClient',
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
      'coreServiceFactory' => 
      array (
        'declaringClassName' => 'Stripe\\StripeClient',
        'implementingClassName' => 'Stripe\\StripeClient',
        'name' => 'coreServiceFactory',
        'modifiers' => 4,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * @var Service\\CoreServiceFactory
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 87,
        'endLine' => 87,
        'startColumn' => 5,
        'endColumn' => 32,
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
      '__get' => 
      array (
        'name' => '__get',
        'parameters' => 
        array (
          'name' => 
          array (
            'name' => 'name',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 89,
            'endLine' => 89,
            'startColumn' => 27,
            'endColumn' => 31,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 89,
        'endLine' => 92,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\StripeClient',
        'implementingClassName' => 'Stripe\\StripeClient',
        'currentClassName' => 'Stripe\\StripeClient',
        'aliasName' => NULL,
      ),
      'getService' => 
      array (
        'name' => 'getService',
        'parameters' => 
        array (
          'name' => 
          array (
            'name' => 'name',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 94,
            'endLine' => 94,
            'startColumn' => 32,
            'endColumn' => 36,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 94,
        'endLine' => 101,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\StripeClient',
        'implementingClassName' => 'Stripe\\StripeClient',
        'currentClassName' => 'Stripe\\StripeClient',
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