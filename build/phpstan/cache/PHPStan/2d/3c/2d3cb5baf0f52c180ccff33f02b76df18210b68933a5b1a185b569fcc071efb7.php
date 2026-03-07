<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/TaxRate.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Stripe\TaxRate
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-2a021a2d5ee333fdba5bdc71e6ce0d04c6c27836e5c6e187adb568fd8ac29321-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Stripe\\TaxRate',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/TaxRate.php',
      ),
    ),
    'namespace' => 'Stripe',
    'name' => 'Stripe\\TaxRate',
    'shortName' => 'TaxRate',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Tax rates can be applied to <a href="/invoicing/taxes/tax-rates">invoices</a>, <a href="/billing/taxes/tax-rates">subscriptions</a> and <a href="/payments/checkout/use-manual-tax-rates">Checkout Sessions</a> to collect tax.
 *
 * Related guide: <a href="/billing/taxes/tax-rates">Tax rates</a>
 *
 * @property string $id Unique identifier for the object.
 * @property string $object String representing the object\'s type. Objects of the same type share the same value.
 * @property bool $active Defaults to <code>true</code>. When set to <code>false</code>, this tax rate cannot be used with new applications or Checkout Sessions, but will still work for subscriptions and invoices that already have it set.
 * @property null|string $country Two-letter country code (<a href="https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2">ISO 3166-1 alpha-2</a>).
 * @property int $created Time at which the object was created. Measured in seconds since the Unix epoch.
 * @property null|string $description An arbitrary string attached to the tax rate for your internal use only. It will not be visible to your customers.
 * @property string $display_name The display name of the tax rates as it will appear to your customer on their receipt email, PDF, and the hosted invoice page.
 * @property null|float $effective_percentage Actual/effective tax rate percentage out of 100. For tax calculations with automatic_tax[enabled]=true, this percentage reflects the rate actually used to calculate tax based on the product\'s taxability and whether the user is registered to collect taxes in the corresponding jurisdiction.
 * @property null|(object{amount: int, currency: string}&StripeObject) $flat_amount The amount of the tax rate when the <code>rate_type</code> is <code>flat_amount</code>. Tax rates with <code>rate_type</code> <code>percentage</code> can vary based on the transaction, resulting in this field being <code>null</code>. This field exposes the amount and currency of the flat tax rate.
 * @property bool $inclusive This specifies if the tax rate is inclusive or exclusive.
 * @property null|string $jurisdiction The jurisdiction for the tax rate. You can use this label field for tax reporting purposes. It also appears on your customer’s invoice.
 * @property null|string $jurisdiction_level The level of the jurisdiction that imposes this tax rate. Will be <code>null</code> for manually defined tax rates.
 * @property bool $livemode Has the value <code>true</code> if the object exists in live mode or the value <code>false</code> if the object exists in test mode.
 * @property null|StripeObject $metadata Set of <a href="https://stripe.com/docs/api/metadata">key-value pairs</a> that you can attach to an object. This can be useful for storing additional information about the object in a structured format.
 * @property float $percentage Tax rate percentage out of 100. For tax calculations with automatic_tax[enabled]=true, this percentage includes the statutory tax rate of non-taxable jurisdictions.
 * @property null|string $rate_type Indicates the type of tax rate applied to the taxable amount. This value can be <code>null</code> when no tax applies to the location. This field is only present for TaxRates created by Stripe Tax.
 * @property null|string $state <a href="https://en.wikipedia.org/wiki/ISO_3166-2">ISO 3166-2 subdivision code</a>, without country prefix. For example, &quot;NY&quot; for New York, United States.
 * @property null|string $tax_type The high-level tax type, such as <code>vat</code> or <code>sales_tax</code>.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 31,
    'endLine' => 143,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Stripe\\ApiResource',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Stripe\\ApiOperations\\Update',
    ),
    'immediateConstants' => 
    array (
      'OBJECT_NAME' => 
      array (
        'declaringClassName' => 'Stripe\\TaxRate',
        'implementingClassName' => 'Stripe\\TaxRate',
        'name' => 'OBJECT_NAME',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'tax_rate\'',
          'attributes' => 
          array (
            'startLine' => 33,
            'endLine' => 33,
            'startTokenPos' => 27,
            'startFilePos' => 3697,
            'endTokenPos' => 27,
            'endFilePos' => 3706,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 33,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 35,
      ),
      'JURISDICTION_LEVEL_CITY' => 
      array (
        'declaringClassName' => 'Stripe\\TaxRate',
        'implementingClassName' => 'Stripe\\TaxRate',
        'name' => 'JURISDICTION_LEVEL_CITY',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'city\'',
          'attributes' => 
          array (
            'startLine' => 37,
            'endLine' => 37,
            'startTokenPos' => 41,
            'startFilePos' => 3777,
            'endTokenPos' => 41,
            'endFilePos' => 3782,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 37,
        'endLine' => 37,
        'startColumn' => 5,
        'endColumn' => 43,
      ),
      'JURISDICTION_LEVEL_COUNTRY' => 
      array (
        'declaringClassName' => 'Stripe\\TaxRate',
        'implementingClassName' => 'Stripe\\TaxRate',
        'name' => 'JURISDICTION_LEVEL_COUNTRY',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'country\'',
          'attributes' => 
          array (
            'startLine' => 38,
            'endLine' => 38,
            'startTokenPos' => 50,
            'startFilePos' => 3824,
            'endTokenPos' => 50,
            'endFilePos' => 3832,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 38,
        'endLine' => 38,
        'startColumn' => 5,
        'endColumn' => 49,
      ),
      'JURISDICTION_LEVEL_COUNTY' => 
      array (
        'declaringClassName' => 'Stripe\\TaxRate',
        'implementingClassName' => 'Stripe\\TaxRate',
        'name' => 'JURISDICTION_LEVEL_COUNTY',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'county\'',
          'attributes' => 
          array (
            'startLine' => 39,
            'endLine' => 39,
            'startTokenPos' => 59,
            'startFilePos' => 3873,
            'endTokenPos' => 59,
            'endFilePos' => 3880,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 39,
        'endLine' => 39,
        'startColumn' => 5,
        'endColumn' => 47,
      ),
      'JURISDICTION_LEVEL_DISTRICT' => 
      array (
        'declaringClassName' => 'Stripe\\TaxRate',
        'implementingClassName' => 'Stripe\\TaxRate',
        'name' => 'JURISDICTION_LEVEL_DISTRICT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'district\'',
          'attributes' => 
          array (
            'startLine' => 40,
            'endLine' => 40,
            'startTokenPos' => 68,
            'startFilePos' => 3923,
            'endTokenPos' => 68,
            'endFilePos' => 3932,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 40,
        'endLine' => 40,
        'startColumn' => 5,
        'endColumn' => 51,
      ),
      'JURISDICTION_LEVEL_MULTIPLE' => 
      array (
        'declaringClassName' => 'Stripe\\TaxRate',
        'implementingClassName' => 'Stripe\\TaxRate',
        'name' => 'JURISDICTION_LEVEL_MULTIPLE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'multiple\'',
          'attributes' => 
          array (
            'startLine' => 41,
            'endLine' => 41,
            'startTokenPos' => 77,
            'startFilePos' => 3975,
            'endTokenPos' => 77,
            'endFilePos' => 3984,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 41,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 51,
      ),
      'JURISDICTION_LEVEL_STATE' => 
      array (
        'declaringClassName' => 'Stripe\\TaxRate',
        'implementingClassName' => 'Stripe\\TaxRate',
        'name' => 'JURISDICTION_LEVEL_STATE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'state\'',
          'attributes' => 
          array (
            'startLine' => 42,
            'endLine' => 42,
            'startTokenPos' => 86,
            'startFilePos' => 4024,
            'endTokenPos' => 86,
            'endFilePos' => 4030,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 42,
        'endLine' => 42,
        'startColumn' => 5,
        'endColumn' => 45,
      ),
      'RATE_TYPE_FLAT_AMOUNT' => 
      array (
        'declaringClassName' => 'Stripe\\TaxRate',
        'implementingClassName' => 'Stripe\\TaxRate',
        'name' => 'RATE_TYPE_FLAT_AMOUNT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'flat_amount\'',
          'attributes' => 
          array (
            'startLine' => 44,
            'endLine' => 44,
            'startTokenPos' => 95,
            'startFilePos' => 4068,
            'endTokenPos' => 95,
            'endFilePos' => 4080,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 44,
        'endLine' => 44,
        'startColumn' => 5,
        'endColumn' => 48,
      ),
      'RATE_TYPE_PERCENTAGE' => 
      array (
        'declaringClassName' => 'Stripe\\TaxRate',
        'implementingClassName' => 'Stripe\\TaxRate',
        'name' => 'RATE_TYPE_PERCENTAGE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'percentage\'',
          'attributes' => 
          array (
            'startLine' => 45,
            'endLine' => 45,
            'startTokenPos' => 104,
            'startFilePos' => 4116,
            'endTokenPos' => 104,
            'endFilePos' => 4127,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 45,
        'endLine' => 45,
        'startColumn' => 5,
        'endColumn' => 46,
      ),
      'TAX_TYPE_AMUSEMENT_TAX' => 
      array (
        'declaringClassName' => 'Stripe\\TaxRate',
        'implementingClassName' => 'Stripe\\TaxRate',
        'name' => 'TAX_TYPE_AMUSEMENT_TAX',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'amusement_tax\'',
          'attributes' => 
          array (
            'startLine' => 47,
            'endLine' => 47,
            'startTokenPos' => 113,
            'startFilePos' => 4166,
            'endTokenPos' => 113,
            'endFilePos' => 4180,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 47,
        'endLine' => 47,
        'startColumn' => 5,
        'endColumn' => 51,
      ),
      'TAX_TYPE_COMMUNICATIONS_TAX' => 
      array (
        'declaringClassName' => 'Stripe\\TaxRate',
        'implementingClassName' => 'Stripe\\TaxRate',
        'name' => 'TAX_TYPE_COMMUNICATIONS_TAX',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'communications_tax\'',
          'attributes' => 
          array (
            'startLine' => 48,
            'endLine' => 48,
            'startTokenPos' => 122,
            'startFilePos' => 4223,
            'endTokenPos' => 122,
            'endFilePos' => 4242,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 48,
        'endLine' => 48,
        'startColumn' => 5,
        'endColumn' => 61,
      ),
      'TAX_TYPE_GST' => 
      array (
        'declaringClassName' => 'Stripe\\TaxRate',
        'implementingClassName' => 'Stripe\\TaxRate',
        'name' => 'TAX_TYPE_GST',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'gst\'',
          'attributes' => 
          array (
            'startLine' => 49,
            'endLine' => 49,
            'startTokenPos' => 131,
            'startFilePos' => 4270,
            'endTokenPos' => 131,
            'endFilePos' => 4274,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 49,
        'endLine' => 49,
        'startColumn' => 5,
        'endColumn' => 31,
      ),
      'TAX_TYPE_HST' => 
      array (
        'declaringClassName' => 'Stripe\\TaxRate',
        'implementingClassName' => 'Stripe\\TaxRate',
        'name' => 'TAX_TYPE_HST',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'hst\'',
          'attributes' => 
          array (
            'startLine' => 50,
            'endLine' => 50,
            'startTokenPos' => 140,
            'startFilePos' => 4302,
            'endTokenPos' => 140,
            'endFilePos' => 4306,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 50,
        'endLine' => 50,
        'startColumn' => 5,
        'endColumn' => 31,
      ),
      'TAX_TYPE_IGST' => 
      array (
        'declaringClassName' => 'Stripe\\TaxRate',
        'implementingClassName' => 'Stripe\\TaxRate',
        'name' => 'TAX_TYPE_IGST',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'igst\'',
          'attributes' => 
          array (
            'startLine' => 51,
            'endLine' => 51,
            'startTokenPos' => 149,
            'startFilePos' => 4335,
            'endTokenPos' => 149,
            'endFilePos' => 4340,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 51,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'TAX_TYPE_JCT' => 
      array (
        'declaringClassName' => 'Stripe\\TaxRate',
        'implementingClassName' => 'Stripe\\TaxRate',
        'name' => 'TAX_TYPE_JCT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'jct\'',
          'attributes' => 
          array (
            'startLine' => 52,
            'endLine' => 52,
            'startTokenPos' => 158,
            'startFilePos' => 4368,
            'endTokenPos' => 158,
            'endFilePos' => 4372,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 52,
        'endLine' => 52,
        'startColumn' => 5,
        'endColumn' => 31,
      ),
      'TAX_TYPE_LEASE_TAX' => 
      array (
        'declaringClassName' => 'Stripe\\TaxRate',
        'implementingClassName' => 'Stripe\\TaxRate',
        'name' => 'TAX_TYPE_LEASE_TAX',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'lease_tax\'',
          'attributes' => 
          array (
            'startLine' => 53,
            'endLine' => 53,
            'startTokenPos' => 167,
            'startFilePos' => 4406,
            'endTokenPos' => 167,
            'endFilePos' => 4416,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 53,
        'endLine' => 53,
        'startColumn' => 5,
        'endColumn' => 43,
      ),
      'TAX_TYPE_PST' => 
      array (
        'declaringClassName' => 'Stripe\\TaxRate',
        'implementingClassName' => 'Stripe\\TaxRate',
        'name' => 'TAX_TYPE_PST',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'pst\'',
          'attributes' => 
          array (
            'startLine' => 54,
            'endLine' => 54,
            'startTokenPos' => 176,
            'startFilePos' => 4444,
            'endTokenPos' => 176,
            'endFilePos' => 4448,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 54,
        'endLine' => 54,
        'startColumn' => 5,
        'endColumn' => 31,
      ),
      'TAX_TYPE_QST' => 
      array (
        'declaringClassName' => 'Stripe\\TaxRate',
        'implementingClassName' => 'Stripe\\TaxRate',
        'name' => 'TAX_TYPE_QST',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'qst\'',
          'attributes' => 
          array (
            'startLine' => 55,
            'endLine' => 55,
            'startTokenPos' => 185,
            'startFilePos' => 4476,
            'endTokenPos' => 185,
            'endFilePos' => 4480,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 55,
        'endLine' => 55,
        'startColumn' => 5,
        'endColumn' => 31,
      ),
      'TAX_TYPE_RETAIL_DELIVERY_FEE' => 
      array (
        'declaringClassName' => 'Stripe\\TaxRate',
        'implementingClassName' => 'Stripe\\TaxRate',
        'name' => 'TAX_TYPE_RETAIL_DELIVERY_FEE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'retail_delivery_fee\'',
          'attributes' => 
          array (
            'startLine' => 56,
            'endLine' => 56,
            'startTokenPos' => 194,
            'startFilePos' => 4524,
            'endTokenPos' => 194,
            'endFilePos' => 4544,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 56,
        'endLine' => 56,
        'startColumn' => 5,
        'endColumn' => 63,
      ),
      'TAX_TYPE_RST' => 
      array (
        'declaringClassName' => 'Stripe\\TaxRate',
        'implementingClassName' => 'Stripe\\TaxRate',
        'name' => 'TAX_TYPE_RST',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'rst\'',
          'attributes' => 
          array (
            'startLine' => 57,
            'endLine' => 57,
            'startTokenPos' => 203,
            'startFilePos' => 4572,
            'endTokenPos' => 203,
            'endFilePos' => 4576,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 57,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 31,
      ),
      'TAX_TYPE_SALES_TAX' => 
      array (
        'declaringClassName' => 'Stripe\\TaxRate',
        'implementingClassName' => 'Stripe\\TaxRate',
        'name' => 'TAX_TYPE_SALES_TAX',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'sales_tax\'',
          'attributes' => 
          array (
            'startLine' => 58,
            'endLine' => 58,
            'startTokenPos' => 212,
            'startFilePos' => 4610,
            'endTokenPos' => 212,
            'endFilePos' => 4620,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 58,
        'endLine' => 58,
        'startColumn' => 5,
        'endColumn' => 43,
      ),
      'TAX_TYPE_SERVICE_TAX' => 
      array (
        'declaringClassName' => 'Stripe\\TaxRate',
        'implementingClassName' => 'Stripe\\TaxRate',
        'name' => 'TAX_TYPE_SERVICE_TAX',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'service_tax\'',
          'attributes' => 
          array (
            'startLine' => 59,
            'endLine' => 59,
            'startTokenPos' => 221,
            'startFilePos' => 4656,
            'endTokenPos' => 221,
            'endFilePos' => 4668,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 59,
        'endLine' => 59,
        'startColumn' => 5,
        'endColumn' => 47,
      ),
      'TAX_TYPE_VAT' => 
      array (
        'declaringClassName' => 'Stripe\\TaxRate',
        'implementingClassName' => 'Stripe\\TaxRate',
        'name' => 'TAX_TYPE_VAT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'vat\'',
          'attributes' => 
          array (
            'startLine' => 60,
            'endLine' => 60,
            'startTokenPos' => 230,
            'startFilePos' => 4696,
            'endTokenPos' => 230,
            'endFilePos' => 4700,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 60,
        'endLine' => 60,
        'startColumn' => 5,
        'endColumn' => 31,
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
                'startLine' => 72,
                'endLine' => 72,
                'startTokenPos' => 247,
                'startFilePos' => 5222,
                'endTokenPos' => 247,
                'endFilePos' => 5225,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 72,
            'endLine' => 72,
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
                'startLine' => 72,
                'endLine' => 72,
                'startTokenPos' => 254,
                'startFilePos' => 5239,
                'endTokenPos' => 254,
                'endFilePos' => 5242,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 72,
            'endLine' => 72,
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
 * Creates a new tax rate.
 *
 * @param null|array{active?: bool, country?: string, description?: string, display_name: string, expand?: string[], inclusive: bool, jurisdiction?: string, metadata?: array<string, string>, percentage: float, state?: string, tax_type?: string} $params
 * @param null|array|string $options
 *
 * @return TaxRate the created resource
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 72,
        'endLine' => 82,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\TaxRate',
        'implementingClassName' => 'Stripe\\TaxRate',
        'currentClassName' => 'Stripe\\TaxRate',
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
                'startLine' => 95,
                'endLine' => 95,
                'startTokenPos' => 351,
                'startFilePos' => 6114,
                'endTokenPos' => 351,
                'endFilePos' => 6117,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 95,
            'endLine' => 95,
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
                'startLine' => 95,
                'endLine' => 95,
                'startTokenPos' => 358,
                'startFilePos' => 6128,
                'endTokenPos' => 358,
                'endFilePos' => 6131,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 95,
            'endLine' => 95,
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
 * Returns a list of your tax rates. Tax rates are returned sorted by creation
 * date, with the most recently created tax rates appearing first.
 *
 * @param null|array{active?: bool, created?: array|int, ending_before?: string, expand?: string[], inclusive?: bool, limit?: int, starting_after?: string} $params
 * @param null|array|string $opts
 *
 * @return Collection<TaxRate> of ApiResources
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 95,
        'endLine' => 100,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\TaxRate',
        'implementingClassName' => 'Stripe\\TaxRate',
        'currentClassName' => 'Stripe\\TaxRate',
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
            'startLine' => 112,
            'endLine' => 112,
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
                'startLine' => 112,
                'endLine' => 112,
                'startTokenPos' => 414,
                'startFilePos' => 6633,
                'endTokenPos' => 414,
                'endFilePos' => 6636,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 112,
            'endLine' => 112,
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
 * Retrieves a tax rate with the given ID.
 *
 * @param array|string $id the ID of the API resource to retrieve, or an options array containing an `id` key
 * @param null|array|string $opts
 *
 * @return TaxRate
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 112,
        'endLine' => 119,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\TaxRate',
        'implementingClassName' => 'Stripe\\TaxRate',
        'currentClassName' => 'Stripe\\TaxRate',
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
            'startLine' => 132,
            'endLine' => 132,
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
                'startLine' => 132,
                'endLine' => 132,
                'startTokenPos' => 477,
                'startFilePos' => 7358,
                'endTokenPos' => 477,
                'endFilePos' => 7361,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 132,
            'endLine' => 132,
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
                'startLine' => 132,
                'endLine' => 132,
                'startTokenPos' => 484,
                'startFilePos' => 7372,
                'endTokenPos' => 484,
                'endFilePos' => 7375,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 132,
            'endLine' => 132,
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
 * Updates an existing tax rate.
 *
 * @param string $id the ID of the resource to update
 * @param null|array{active?: bool, country?: string, description?: string, display_name?: string, expand?: string[], jurisdiction?: string, metadata?: null|array<string, string>, state?: string, tax_type?: string} $params
 * @param null|array|string $opts
 *
 * @return TaxRate the updated resource
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 132,
        'endLine' => 142,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\TaxRate',
        'implementingClassName' => 'Stripe\\TaxRate',
        'currentClassName' => 'Stripe\\TaxRate',
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