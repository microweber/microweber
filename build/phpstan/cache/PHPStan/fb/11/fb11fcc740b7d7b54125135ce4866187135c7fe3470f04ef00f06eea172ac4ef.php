<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/Invoice.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Stripe\Invoice
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-464ebd9cc1b26eb4a40643feeed0fbb22e4fb467f7c4c3141a5f25e56cc6322e-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Stripe\\Invoice',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/Invoice.php',
      ),
    ),
    'namespace' => 'Stripe',
    'name' => 'Stripe\\Invoice',
    'shortName' => 'Invoice',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Invoices are statements of amounts owed by a customer, and are either
 * generated one-off, or generated periodically from a subscription.
 *
 * They contain <a href="https://stripe.com/docs/api#invoiceitems">invoice items</a>, and proration adjustments
 * that may be caused by subscription upgrades/downgrades (if necessary).
 *
 * If your invoice is configured to be billed through automatic charges,
 * Stripe automatically finalizes your invoice and attempts payment. Note
 * that finalizing the invoice,
 * <a href="https://stripe.com/docs/invoicing/integration/automatic-advancement-collection">when automatic</a>, does
 * not happen immediately as the invoice is created. Stripe waits
 * until one hour after the last webhook was successfully sent (or the last
 * webhook timed out after failing). If you (and the platforms you may have
 * connected to) have no webhooks configured, Stripe waits one hour after
 * creation to finalize the invoice.
 *
 * If your invoice is configured to be billed by sending an email, then based on your
 * <a href="https://dashboard.stripe.com/account/billing/automatic">email settings</a>,
 * Stripe will email the invoice to your customer and await payment. These
 * emails can contain a link to a hosted page to pay the invoice.
 *
 * Stripe applies any customer credit on the account before determining the
 * amount due for the invoice (i.e., the amount that will be actually
 * charged). If the amount due for the invoice is less than Stripe\'s <a href="/docs/currencies#minimum-and-maximum-charge-amounts">minimum allowed charge
 * per currency</a>, the
 * invoice is automatically marked paid, and we add the amount due to the
 * customer\'s credit balance which is applied to the next invoice.
 *
 * More details on the customer\'s credit balance are
 * <a href="https://stripe.com/docs/billing/customer/balance">here</a>.
 *
 * Related guide: <a href="https://stripe.com/docs/billing/invoices/sending">Send invoices to customers</a>
 *
 * @property null|string $id Unique identifier for the object. For preview invoices created using the <a href="https://stripe.com/docs/api/invoices/create_preview">create preview</a> endpoint, this id will be prefixed with <code>upcoming_in</code>.
 * @property string $object String representing the object\'s type. Objects of the same type share the same value.
 * @property null|string $account_country The country of the business associated with this invoice, most often the business creating the invoice.
 * @property null|string $account_name The public name of the business associated with this invoice, most often the business creating the invoice.
 * @property null|(string|TaxId)[] $account_tax_ids The account tax IDs associated with the invoice. Only editable when the invoice is a draft.
 * @property int $amount_due Final amount due at this time for this invoice. If the invoice\'s total is smaller than the minimum charge amount, for example, or if there is account credit that can be applied to the invoice, the <code>amount_due</code> may be 0. If there is a positive <code>starting_balance</code> for the invoice (the customer owes money), the <code>amount_due</code> will also take that into account. The charge that gets generated for the invoice will be for the amount specified in <code>amount_due</code>.
 * @property int $amount_overpaid Amount that was overpaid on the invoice. The amount overpaid is credited to the customer\'s credit balance.
 * @property int $amount_paid The amount, in cents (or local equivalent), that was paid.
 * @property int $amount_remaining The difference between amount_due and amount_paid, in cents (or local equivalent).
 * @property int $amount_shipping This is the sum of all the shipping amounts.
 * @property null|Application|string $application ID of the Connect Application that created the invoice.
 * @property int $attempt_count Number of payment attempts made for this invoice, from the perspective of the payment retry schedule. Any payment attempt counts as the first attempt, and subsequently only automatic retries increment the attempt count. In other words, manual payment attempts after the first attempt do not affect the retry schedule. If a failure is returned with a non-retryable return code, the invoice can no longer be retried unless a new payment method is obtained. Retries will continue to be scheduled, and attempt_count will continue to increment, but retries will only be executed if a new payment method is obtained.
 * @property bool $attempted Whether an attempt has been made to pay the invoice. An invoice is not attempted until 1 hour after the <code>invoice.created</code> webhook, for example, so you might not want to display that invoice as unpaid to your users.
 * @property null|bool $auto_advance Controls whether Stripe performs <a href="https://stripe.com/docs/invoicing/integration/automatic-advancement-collection">automatic collection</a> of the invoice. If <code>false</code>, the invoice\'s state doesn\'t automatically advance without an explicit action.
 * @property (object{disabled_reason: null|string, enabled: bool, liability: null|(object{account?: Account|string, type: string}&StripeObject), provider: null|string, status: null|string}&StripeObject) $automatic_tax
 * @property null|int $automatically_finalizes_at The time when this invoice is currently scheduled to be automatically finalized. The field will be <code>null</code> if the invoice is not scheduled to finalize in the future. If the invoice is not in the draft state, this field will always be <code>null</code> - see <code>finalized_at</code> for the time when an already-finalized invoice was finalized.
 * @property null|string $billing_reason <p>Indicates the reason why the invoice was created.</p><p>* <code>manual</code>: Unrelated to a subscription, for example, created via the invoice editor. * <code>subscription</code>: No longer in use. Applies to subscriptions from before May 2018 where no distinction was made between updates, cycles, and thresholds. * <code>subscription_create</code>: A new subscription was created. * <code>subscription_cycle</code>: A subscription advanced into a new period. * <code>subscription_threshold</code>: A subscription reached a billing threshold. * <code>subscription_update</code>: A subscription was updated. * <code>upcoming</code>: Reserved for simulated invoices, per the upcoming invoice endpoint.</p>
 * @property string $collection_method Either <code>charge_automatically</code>, or <code>send_invoice</code>. When charging automatically, Stripe will attempt to pay this invoice using the default source attached to the customer. When sending an invoice, Stripe will email this invoice to the customer with payment instructions.
 * @property null|(object{client_secret: string, type: string}&StripeObject) $confirmation_secret The confirmation secret associated with this invoice. Currently, this contains the client_secret of the PaymentIntent that Stripe creates during invoice finalization.
 * @property int $created Time at which the object was created. Measured in seconds since the Unix epoch.
 * @property string $currency Three-letter <a href="https://www.iso.org/iso-4217-currency-codes.html">ISO currency code</a>, in lowercase. Must be a <a href="https://stripe.com/docs/currencies">supported currency</a>.
 * @property null|(object{name: string, value: string}&StripeObject)[] $custom_fields Custom fields displayed on the invoice.
 * @property null|Customer|string $customer The ID of the customer who will be billed.
 * @property null|(object{city: null|string, country: null|string, line1: null|string, line2: null|string, postal_code: null|string, state: null|string}&StripeObject) $customer_address The customer\'s address. Until the invoice is finalized, this field will equal <code>customer.address</code>. Once the invoice is finalized, this field will no longer be updated.
 * @property null|string $customer_email The customer\'s email. Until the invoice is finalized, this field will equal <code>customer.email</code>. Once the invoice is finalized, this field will no longer be updated.
 * @property null|string $customer_name The customer\'s name. Until the invoice is finalized, this field will equal <code>customer.name</code>. Once the invoice is finalized, this field will no longer be updated.
 * @property null|string $customer_phone The customer\'s phone number. Until the invoice is finalized, this field will equal <code>customer.phone</code>. Once the invoice is finalized, this field will no longer be updated.
 * @property null|(object{address?: (object{city: null|string, country: null|string, line1: null|string, line2: null|string, postal_code: null|string, state: null|string}&StripeObject), carrier?: null|string, name?: string, phone?: null|string, tracking_number?: null|string}&StripeObject) $customer_shipping The customer\'s shipping information. Until the invoice is finalized, this field will equal <code>customer.shipping</code>. Once the invoice is finalized, this field will no longer be updated.
 * @property null|string $customer_tax_exempt The customer\'s tax exempt status. Until the invoice is finalized, this field will equal <code>customer.tax_exempt</code>. Once the invoice is finalized, this field will no longer be updated.
 * @property null|((object{type: string, value: null|string}&StripeObject))[] $customer_tax_ids The customer\'s tax IDs. Until the invoice is finalized, this field will contain the same tax IDs as <code>customer.tax_ids</code>. Once the invoice is finalized, this field will no longer be updated.
 * @property null|PaymentMethod|string $default_payment_method ID of the default payment method for the invoice. It must belong to the customer associated with the invoice. If not set, defaults to the subscription\'s default payment method, if any, or to the default payment method in the customer\'s invoice settings.
 * @property null|Account|BankAccount|Card|Source|string $default_source ID of the default payment source for the invoice. It must belong to the customer associated with the invoice and be in a chargeable state. If not set, defaults to the subscription\'s default source, if any, or to the customer\'s default source.
 * @property TaxRate[] $default_tax_rates The tax rates applied to this invoice, if any.
 * @property null|string $description An arbitrary string attached to the object. Often useful for displaying to users. Referenced as \'memo\' in the Dashboard.
 * @property (Discount|string)[] $discounts The discounts applied to the invoice. Line item discounts are applied before invoice discounts. Use <code>expand[]=discounts</code> to expand each discount.
 * @property null|int $due_date The date on which payment for this invoice is due. This value will be <code>null</code> for invoices where <code>collection_method=charge_automatically</code>.
 * @property null|int $effective_at The date when this invoice is in effect. Same as <code>finalized_at</code> unless overwritten. When defined, this value replaces the system-generated \'Date of issue\' printed on the invoice PDF and receipt.
 * @property null|int $ending_balance Ending customer balance after the invoice is finalized. Invoices are finalized approximately an hour after successful webhook delivery or when payment collection is attempted for the invoice. If the invoice has not been finalized yet, this will be null.
 * @property null|string $footer Footer displayed on the invoice.
 * @property null|(object{action: string, invoice: Invoice|string}&StripeObject) $from_invoice Details of the invoice that was cloned. See the <a href="https://stripe.com/docs/invoicing/invoice-revisions">revision documentation</a> for more details.
 * @property null|string $hosted_invoice_url The URL for the hosted invoice page, which allows customers to view and pay an invoice. If the invoice has not been finalized yet, this will be null.
 * @property null|string $invoice_pdf The link to download the PDF for the invoice. If the invoice has not been finalized yet, this will be null.
 * @property (object{account?: Account|string, type: string}&StripeObject) $issuer
 * @property null|(object{advice_code?: string, charge?: string, code?: string, decline_code?: string, doc_url?: string, message?: string, network_advice_code?: string, network_decline_code?: string, param?: string, payment_intent?: PaymentIntent, payment_method?: PaymentMethod, payment_method_type?: string, request_log_url?: string, setup_intent?: SetupIntent, source?: Account|BankAccount|Card|Source, type: string}&StripeObject) $last_finalization_error The error encountered during the previous attempt to finalize the invoice. This field is cleared when the invoice is successfully finalized.
 * @property null|Invoice|string $latest_revision The ID of the most recent non-draft revision of this invoice
 * @property Collection<InvoiceLineItem> $lines The individual line items that make up the invoice. <code>lines</code> is sorted as follows: (1) pending invoice items (including prorations) in reverse chronological order, (2) subscription items in reverse chronological order, and (3) invoice items added after invoice creation in chronological order.
 * @property bool $livemode Has the value <code>true</code> if the object exists in live mode or the value <code>false</code> if the object exists in test mode.
 * @property null|StripeObject $metadata Set of <a href="https://stripe.com/docs/api/metadata">key-value pairs</a> that you can attach to an object. This can be useful for storing additional information about the object in a structured format.
 * @property null|int $next_payment_attempt The time at which payment will next be attempted. This value will be <code>null</code> for invoices where <code>collection_method=send_invoice</code>.
 * @property null|string $number A unique, identifying string that appears on emails sent to the customer for this invoice. This starts with the customer\'s unique invoice_prefix if it is specified.
 * @property null|Account|string $on_behalf_of The account (if any) for which the funds of the invoice payment are intended. If set, the invoice will be presented with the branding and support information of the specified account. See the <a href="https://stripe.com/docs/billing/invoices/connect">Invoices with Connect</a> documentation for details.
 * @property null|(object{quote_details: null|(object{quote: string}&StripeObject), subscription_details: null|(object{metadata: null|StripeObject, subscription: string|Subscription, subscription_proration_date?: int}&StripeObject), type: string}&StripeObject) $parent The parent that generated this invoice
 * @property (object{default_mandate: null|string, payment_method_options: null|(object{acss_debit: null|(object{mandate_options?: (object{transaction_type: null|string}&StripeObject), verification_method?: string}&StripeObject), bancontact: null|(object{preferred_language: string}&StripeObject), card: null|(object{installments?: (object{enabled: null|bool}&StripeObject), request_three_d_secure: null|string}&StripeObject), customer_balance: null|(object{bank_transfer?: (object{eu_bank_transfer?: (object{country: string}&StripeObject), type: null|string}&StripeObject), funding_type: null|string}&StripeObject), konbini: null|(object{}&StripeObject), sepa_debit: null|(object{}&StripeObject), us_bank_account: null|(object{financial_connections?: (object{filters?: (object{account_subcategories?: string[]}&StripeObject), permissions?: string[], prefetch: null|string[]}&StripeObject), verification_method?: string}&StripeObject)}&StripeObject), payment_method_types: null|string[]}&StripeObject) $payment_settings
 * @property null|Collection<InvoicePayment> $payments Payments for this invoice
 * @property int $period_end End of the usage period during which invoice items were added to this invoice. This looks back one period for a subscription invoice. Use the <a href="/api/invoices/line_item#invoice_line_item_object-period">line item period</a> to get the service period for each price.
 * @property int $period_start Start of the usage period during which invoice items were added to this invoice. This looks back one period for a subscription invoice. Use the <a href="/api/invoices/line_item#invoice_line_item_object-period">line item period</a> to get the service period for each price.
 * @property int $post_payment_credit_notes_amount Total amount of all post-payment credit notes issued for this invoice.
 * @property int $pre_payment_credit_notes_amount Total amount of all pre-payment credit notes issued for this invoice.
 * @property null|string $receipt_number This is the transaction number that appears on email receipts sent for this invoice.
 * @property null|(object{amount_tax_display: null|string, pdf: null|(object{page_size: null|string}&StripeObject), template: null|string, template_version: null|int}&StripeObject) $rendering The rendering-related settings that control how the invoice is displayed on customer-facing surfaces such as PDF and Hosted Invoice Page.
 * @property null|(object{amount_subtotal: int, amount_tax: int, amount_total: int, shipping_rate: null|ShippingRate|string, taxes?: ((object{amount: int, rate: TaxRate, taxability_reason: null|string, taxable_amount: null|int}&StripeObject))[]}&StripeObject) $shipping_cost The details of the cost of shipping, including the ShippingRate applied on the invoice.
 * @property null|(object{address?: (object{city: null|string, country: null|string, line1: null|string, line2: null|string, postal_code: null|string, state: null|string}&StripeObject), carrier?: null|string, name?: string, phone?: null|string, tracking_number?: null|string}&StripeObject) $shipping_details Shipping details for the invoice. The Invoice PDF will use the <code>shipping_details</code> value if it is set, otherwise the PDF will render the shipping address from the customer.
 * @property int $starting_balance Starting customer balance before the invoice is finalized. If the invoice has not been finalized yet, this will be the current customer balance. For revision invoices, this also includes any customer balance that was applied to the original invoice.
 * @property null|string $statement_descriptor Extra information about an invoice for the customer\'s credit card statement.
 * @property null|string $status The status of the invoice, one of <code>draft</code>, <code>open</code>, <code>paid</code>, <code>uncollectible</code>, or <code>void</code>. <a href="https://stripe.com/docs/billing/invoices/workflow#workflow-overview">Learn more</a>
 * @property (object{finalized_at: null|int, marked_uncollectible_at: null|int, paid_at: null|int, voided_at: null|int}&StripeObject) $status_transitions
 * @property int $subtotal Total of all subscriptions, invoice items, and prorations on the invoice before any invoice level discount or exclusive tax is applied. Item discounts are already incorporated
 * @property null|int $subtotal_excluding_tax The integer amount in cents (or local equivalent) representing the subtotal of the invoice before any invoice level discount or tax is applied. Item discounts are already incorporated
 * @property null|string|TestHelpers\\TestClock $test_clock ID of the test clock this invoice belongs to.
 * @property null|(object{amount_gte: null|int, item_reasons: (object{line_item_ids: string[], usage_gte: int}&StripeObject)[]}&StripeObject) $threshold_reason
 * @property int $total Total after discounts and taxes.
 * @property null|((object{amount: int, discount: Discount|string}&StripeObject))[] $total_discount_amounts The aggregate amounts calculated per discount across all line items.
 * @property null|int $total_excluding_tax The integer amount in cents (or local equivalent) representing the total amount of the invoice including all discounts but excluding all tax.
 * @property null|((object{amount: int, credit_balance_transaction?: null|Billing\\CreditBalanceTransaction|string, discount?: Discount|string, type: string}&StripeObject))[] $total_pretax_credit_amounts Contains pretax credit amounts (ex: discount, credit grants, etc) that apply to this invoice. This is a combined list of total_pretax_credit_amounts across all invoice line items.
 * @property null|((object{amount: int, tax_behavior: string, tax_rate_details: null|(object{tax_rate: string}&StripeObject), taxability_reason: string, taxable_amount: null|int, type: string}&StripeObject))[] $total_taxes The aggregate tax information of all line items.
 * @property null|int $webhooks_delivered_at Invoices are automatically paid or sent 1 hour after webhooks are delivered, or until all webhook delivery attempts have <a href="https://stripe.com/docs/billing/webhooks#understand">been exhausted</a>. This field tracks the time when webhooks for this invoice were successfully delivered. If the invoice had no webhooks to deliver, this will be set while the invoice is being created.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 118,
    'endLine' => 469,
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
        'declaringClassName' => 'Stripe\\Invoice',
        'implementingClassName' => 'Stripe\\Invoice',
        'name' => 'OBJECT_NAME',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'invoice\'',
          'attributes' => 
          array (
            'startLine' => 120,
            'endLine' => 120,
            'startTokenPos' => 27,
            'startFilePos' => 21093,
            'endTokenPos' => 27,
            'endFilePos' => 21101,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 120,
        'endLine' => 120,
        'startColumn' => 5,
        'endColumn' => 34,
      ),
      'BILLING_REASON_AUTOMATIC_PENDING_INVOICE_ITEM_INVOICE' => 
      array (
        'declaringClassName' => 'Stripe\\Invoice',
        'implementingClassName' => 'Stripe\\Invoice',
        'name' => 'BILLING_REASON_AUTOMATIC_PENDING_INVOICE_ITEM_INVOICE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'automatic_pending_invoice_item_invoice\'',
          'attributes' => 
          array (
            'startLine' => 125,
            'endLine' => 125,
            'startTokenPos' => 46,
            'startFilePos' => 21240,
            'endTokenPos' => 46,
            'endFilePos' => 21279,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 125,
        'endLine' => 125,
        'startColumn' => 5,
        'endColumn' => 107,
      ),
      'BILLING_REASON_MANUAL' => 
      array (
        'declaringClassName' => 'Stripe\\Invoice',
        'implementingClassName' => 'Stripe\\Invoice',
        'name' => 'BILLING_REASON_MANUAL',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'manual\'',
          'attributes' => 
          array (
            'startLine' => 126,
            'endLine' => 126,
            'startTokenPos' => 55,
            'startFilePos' => 21316,
            'endTokenPos' => 55,
            'endFilePos' => 21323,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 126,
        'endLine' => 126,
        'startColumn' => 5,
        'endColumn' => 43,
      ),
      'BILLING_REASON_QUOTE_ACCEPT' => 
      array (
        'declaringClassName' => 'Stripe\\Invoice',
        'implementingClassName' => 'Stripe\\Invoice',
        'name' => 'BILLING_REASON_QUOTE_ACCEPT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'quote_accept\'',
          'attributes' => 
          array (
            'startLine' => 127,
            'endLine' => 127,
            'startTokenPos' => 64,
            'startFilePos' => 21366,
            'endTokenPos' => 64,
            'endFilePos' => 21379,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 127,
        'endLine' => 127,
        'startColumn' => 5,
        'endColumn' => 55,
      ),
      'BILLING_REASON_SUBSCRIPTION' => 
      array (
        'declaringClassName' => 'Stripe\\Invoice',
        'implementingClassName' => 'Stripe\\Invoice',
        'name' => 'BILLING_REASON_SUBSCRIPTION',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'subscription\'',
          'attributes' => 
          array (
            'startLine' => 128,
            'endLine' => 128,
            'startTokenPos' => 73,
            'startFilePos' => 21422,
            'endTokenPos' => 73,
            'endFilePos' => 21435,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 128,
        'endLine' => 128,
        'startColumn' => 5,
        'endColumn' => 55,
      ),
      'BILLING_REASON_SUBSCRIPTION_CREATE' => 
      array (
        'declaringClassName' => 'Stripe\\Invoice',
        'implementingClassName' => 'Stripe\\Invoice',
        'name' => 'BILLING_REASON_SUBSCRIPTION_CREATE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'subscription_create\'',
          'attributes' => 
          array (
            'startLine' => 129,
            'endLine' => 129,
            'startTokenPos' => 82,
            'startFilePos' => 21485,
            'endTokenPos' => 82,
            'endFilePos' => 21505,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 129,
        'endLine' => 129,
        'startColumn' => 5,
        'endColumn' => 69,
      ),
      'BILLING_REASON_SUBSCRIPTION_CYCLE' => 
      array (
        'declaringClassName' => 'Stripe\\Invoice',
        'implementingClassName' => 'Stripe\\Invoice',
        'name' => 'BILLING_REASON_SUBSCRIPTION_CYCLE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'subscription_cycle\'',
          'attributes' => 
          array (
            'startLine' => 130,
            'endLine' => 130,
            'startTokenPos' => 91,
            'startFilePos' => 21554,
            'endTokenPos' => 91,
            'endFilePos' => 21573,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 130,
        'endLine' => 130,
        'startColumn' => 5,
        'endColumn' => 67,
      ),
      'BILLING_REASON_SUBSCRIPTION_THRESHOLD' => 
      array (
        'declaringClassName' => 'Stripe\\Invoice',
        'implementingClassName' => 'Stripe\\Invoice',
        'name' => 'BILLING_REASON_SUBSCRIPTION_THRESHOLD',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'subscription_threshold\'',
          'attributes' => 
          array (
            'startLine' => 131,
            'endLine' => 131,
            'startTokenPos' => 100,
            'startFilePos' => 21626,
            'endTokenPos' => 100,
            'endFilePos' => 21649,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 131,
        'endLine' => 131,
        'startColumn' => 5,
        'endColumn' => 75,
      ),
      'BILLING_REASON_SUBSCRIPTION_UPDATE' => 
      array (
        'declaringClassName' => 'Stripe\\Invoice',
        'implementingClassName' => 'Stripe\\Invoice',
        'name' => 'BILLING_REASON_SUBSCRIPTION_UPDATE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'subscription_update\'',
          'attributes' => 
          array (
            'startLine' => 132,
            'endLine' => 132,
            'startTokenPos' => 109,
            'startFilePos' => 21699,
            'endTokenPos' => 109,
            'endFilePos' => 21719,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 132,
        'endLine' => 132,
        'startColumn' => 5,
        'endColumn' => 69,
      ),
      'BILLING_REASON_UPCOMING' => 
      array (
        'declaringClassName' => 'Stripe\\Invoice',
        'implementingClassName' => 'Stripe\\Invoice',
        'name' => 'BILLING_REASON_UPCOMING',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'upcoming\'',
          'attributes' => 
          array (
            'startLine' => 133,
            'endLine' => 133,
            'startTokenPos' => 118,
            'startFilePos' => 21758,
            'endTokenPos' => 118,
            'endFilePos' => 21767,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 133,
        'endLine' => 133,
        'startColumn' => 5,
        'endColumn' => 47,
      ),
      'COLLECTION_METHOD_CHARGE_AUTOMATICALLY' => 
      array (
        'declaringClassName' => 'Stripe\\Invoice',
        'implementingClassName' => 'Stripe\\Invoice',
        'name' => 'COLLECTION_METHOD_CHARGE_AUTOMATICALLY',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'charge_automatically\'',
          'attributes' => 
          array (
            'startLine' => 135,
            'endLine' => 135,
            'startTokenPos' => 127,
            'startFilePos' => 21822,
            'endTokenPos' => 127,
            'endFilePos' => 21843,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 135,
        'endLine' => 135,
        'startColumn' => 5,
        'endColumn' => 74,
      ),
      'COLLECTION_METHOD_SEND_INVOICE' => 
      array (
        'declaringClassName' => 'Stripe\\Invoice',
        'implementingClassName' => 'Stripe\\Invoice',
        'name' => 'COLLECTION_METHOD_SEND_INVOICE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'send_invoice\'',
          'attributes' => 
          array (
            'startLine' => 136,
            'endLine' => 136,
            'startTokenPos' => 136,
            'startFilePos' => 21889,
            'endTokenPos' => 136,
            'endFilePos' => 21902,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 136,
        'endLine' => 136,
        'startColumn' => 5,
        'endColumn' => 58,
      ),
      'CUSTOMER_TAX_EXEMPT_EXEMPT' => 
      array (
        'declaringClassName' => 'Stripe\\Invoice',
        'implementingClassName' => 'Stripe\\Invoice',
        'name' => 'CUSTOMER_TAX_EXEMPT_EXEMPT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'exempt\'',
          'attributes' => 
          array (
            'startLine' => 138,
            'endLine' => 138,
            'startTokenPos' => 145,
            'startFilePos' => 21945,
            'endTokenPos' => 145,
            'endFilePos' => 21952,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 138,
        'endLine' => 138,
        'startColumn' => 5,
        'endColumn' => 48,
      ),
      'CUSTOMER_TAX_EXEMPT_NONE' => 
      array (
        'declaringClassName' => 'Stripe\\Invoice',
        'implementingClassName' => 'Stripe\\Invoice',
        'name' => 'CUSTOMER_TAX_EXEMPT_NONE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'none\'',
          'attributes' => 
          array (
            'startLine' => 139,
            'endLine' => 139,
            'startTokenPos' => 154,
            'startFilePos' => 21992,
            'endTokenPos' => 154,
            'endFilePos' => 21997,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 139,
        'endLine' => 139,
        'startColumn' => 5,
        'endColumn' => 44,
      ),
      'CUSTOMER_TAX_EXEMPT_REVERSE' => 
      array (
        'declaringClassName' => 'Stripe\\Invoice',
        'implementingClassName' => 'Stripe\\Invoice',
        'name' => 'CUSTOMER_TAX_EXEMPT_REVERSE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'reverse\'',
          'attributes' => 
          array (
            'startLine' => 140,
            'endLine' => 140,
            'startTokenPos' => 163,
            'startFilePos' => 22040,
            'endTokenPos' => 163,
            'endFilePos' => 22048,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 140,
        'endLine' => 140,
        'startColumn' => 5,
        'endColumn' => 50,
      ),
      'STATUS_DRAFT' => 
      array (
        'declaringClassName' => 'Stripe\\Invoice',
        'implementingClassName' => 'Stripe\\Invoice',
        'name' => 'STATUS_DRAFT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'draft\'',
          'attributes' => 
          array (
            'startLine' => 142,
            'endLine' => 142,
            'startTokenPos' => 172,
            'startFilePos' => 22077,
            'endTokenPos' => 172,
            'endFilePos' => 22083,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 142,
        'endLine' => 142,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'STATUS_OPEN' => 
      array (
        'declaringClassName' => 'Stripe\\Invoice',
        'implementingClassName' => 'Stripe\\Invoice',
        'name' => 'STATUS_OPEN',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'open\'',
          'attributes' => 
          array (
            'startLine' => 143,
            'endLine' => 143,
            'startTokenPos' => 181,
            'startFilePos' => 22110,
            'endTokenPos' => 181,
            'endFilePos' => 22115,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 143,
        'endLine' => 143,
        'startColumn' => 5,
        'endColumn' => 31,
      ),
      'STATUS_PAID' => 
      array (
        'declaringClassName' => 'Stripe\\Invoice',
        'implementingClassName' => 'Stripe\\Invoice',
        'name' => 'STATUS_PAID',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'paid\'',
          'attributes' => 
          array (
            'startLine' => 144,
            'endLine' => 144,
            'startTokenPos' => 190,
            'startFilePos' => 22142,
            'endTokenPos' => 190,
            'endFilePos' => 22147,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 144,
        'endLine' => 144,
        'startColumn' => 5,
        'endColumn' => 31,
      ),
      'STATUS_UNCOLLECTIBLE' => 
      array (
        'declaringClassName' => 'Stripe\\Invoice',
        'implementingClassName' => 'Stripe\\Invoice',
        'name' => 'STATUS_UNCOLLECTIBLE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'uncollectible\'',
          'attributes' => 
          array (
            'startLine' => 145,
            'endLine' => 145,
            'startTokenPos' => 199,
            'startFilePos' => 22183,
            'endTokenPos' => 199,
            'endFilePos' => 22197,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 145,
        'endLine' => 145,
        'startColumn' => 5,
        'endColumn' => 49,
      ),
      'STATUS_VOID' => 
      array (
        'declaringClassName' => 'Stripe\\Invoice',
        'implementingClassName' => 'Stripe\\Invoice',
        'name' => 'STATUS_VOID',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'void\'',
          'attributes' => 
          array (
            'startLine' => 146,
            'endLine' => 146,
            'startTokenPos' => 208,
            'startFilePos' => 22224,
            'endTokenPos' => 208,
            'endFilePos' => 22229,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 146,
        'endLine' => 146,
        'startColumn' => 5,
        'endColumn' => 31,
      ),
      'BILLING_CHARGE_AUTOMATICALLY' => 
      array (
        'declaringClassName' => 'Stripe\\Invoice',
        'implementingClassName' => 'Stripe\\Invoice',
        'name' => 'BILLING_CHARGE_AUTOMATICALLY',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'charge_automatically\'',
          'attributes' => 
          array (
            'startLine' => 265,
            'endLine' => 265,
            'startTokenPos' => 640,
            'startFilePos' => 31450,
            'endTokenPos' => 640,
            'endFilePos' => 31471,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 265,
        'endLine' => 265,
        'startColumn' => 5,
        'endColumn' => 64,
      ),
      'BILLING_SEND_INVOICE' => 
      array (
        'declaringClassName' => 'Stripe\\Invoice',
        'implementingClassName' => 'Stripe\\Invoice',
        'name' => 'BILLING_SEND_INVOICE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'send_invoice\'',
          'attributes' => 
          array (
            'startLine' => 266,
            'endLine' => 266,
            'startTokenPos' => 649,
            'startFilePos' => 31507,
            'endTokenPos' => 649,
            'endFilePos' => 31520,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 266,
        'endLine' => 266,
        'startColumn' => 5,
        'endColumn' => 48,
      ),
      'PATH_LINES' => 
      array (
        'declaringClassName' => 'Stripe\\Invoice',
        'implementingClassName' => 'Stripe\\Invoice',
        'name' => 'PATH_LINES',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'/lines\'',
          'attributes' => 
          array (
            'startLine' => 454,
            'endLine' => 454,
            'startTokenPos' => 1570,
            'startFilePos' => 36903,
            'endTokenPos' => 1570,
            'endFilePos' => 36910,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 454,
        'endLine' => 454,
        'startColumn' => 5,
        'endColumn' => 32,
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
                'startLine' => 161,
                'endLine' => 161,
                'startTokenPos' => 225,
                'startFilePos' => 25313,
                'endTokenPos' => 225,
                'endFilePos' => 25316,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 161,
            'endLine' => 161,
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
                'startLine' => 161,
                'endLine' => 161,
                'startTokenPos' => 232,
                'startFilePos' => 25330,
                'endTokenPos' => 232,
                'endFilePos' => 25333,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 161,
            'endLine' => 161,
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
 * This endpoint creates a draft invoice for a given customer. The invoice remains
 * a draft until you <a href="#finalize_invoice">finalize</a> the invoice, which
 * allows you to <a href="#pay_invoice">pay</a> or <a href="#send_invoice">send</a>
 * the invoice to your customers.
 *
 * @param null|array{account_tax_ids?: null|string[], application_fee_amount?: int, auto_advance?: bool, automatic_tax?: array{enabled: bool, liability?: array{account?: string, type: string}}, automatically_finalizes_at?: int, collection_method?: string, currency?: string, custom_fields?: null|array{name: string, value: string}[], customer?: string, days_until_due?: int, default_payment_method?: string, default_source?: string, default_tax_rates?: string[], description?: string, discounts?: null|array{coupon?: string, discount?: string, promotion_code?: string}[], due_date?: int, effective_at?: int, expand?: string[], footer?: string, from_invoice?: array{action: string, invoice: string}, issuer?: array{account?: string, type: string}, metadata?: null|array<string, string>, number?: string, on_behalf_of?: string, payment_settings?: array{default_mandate?: null|string, payment_method_options?: array{acss_debit?: null|array{mandate_options?: array{transaction_type?: string}, verification_method?: string}, bancontact?: null|array{preferred_language?: string}, card?: null|array{installments?: array{enabled?: bool, plan?: null|array{count?: int, interval?: string, type: string}}, request_three_d_secure?: string}, customer_balance?: null|array{bank_transfer?: array{eu_bank_transfer?: array{country: string}, type?: string}, funding_type?: string}, konbini?: null|array{}, sepa_debit?: null|array{}, us_bank_account?: null|array{financial_connections?: array{filters?: array{account_subcategories?: string[]}, permissions?: string[], prefetch?: string[]}, verification_method?: string}}, payment_method_types?: null|string[]}, pending_invoice_items_behavior?: string, rendering?: array{amount_tax_display?: null|string, pdf?: array{page_size?: string}, template?: string, template_version?: null|int}, shipping_cost?: array{shipping_rate?: string, shipping_rate_data?: array{delivery_estimate?: array{maximum?: array{unit: string, value: int}, minimum?: array{unit: string, value: int}}, display_name: string, fixed_amount?: array{amount: int, currency: string, currency_options?: array<string, array{amount: int, tax_behavior?: string}>}, metadata?: array<string, string>, tax_behavior?: string, tax_code?: string, type?: string}}, shipping_details?: array{address: array{city?: string, country?: string, line1?: string, line2?: string, postal_code?: string, state?: string}, name: string, phone?: null|string}, statement_descriptor?: string, subscription?: string, transfer_data?: array{amount?: int, destination: string}} $params
 * @param null|array|string $options
 *
 * @return Invoice the created resource
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 161,
        'endLine' => 171,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Invoice',
        'implementingClassName' => 'Stripe\\Invoice',
        'currentClassName' => 'Stripe\\Invoice',
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
                'startLine' => 186,
                'endLine' => 186,
                'startTokenPos' => 327,
                'startFilePos' => 26198,
                'endTokenPos' => 327,
                'endFilePos' => 26201,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 186,
            'endLine' => 186,
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
                'startLine' => 186,
                'endLine' => 186,
                'startTokenPos' => 334,
                'startFilePos' => 26212,
                'endTokenPos' => 334,
                'endFilePos' => 26215,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 186,
            'endLine' => 186,
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
 * Permanently deletes a one-off invoice draft. This cannot be undone. Attempts to
 * delete invoices that are no longer in a draft state will fail; once an invoice
 * has been finalized or if an invoice is for a subscription, it must be <a
 * href="#void_invoice">voided</a>.
 *
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return Invoice the deleted resource
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 186,
        'endLine' => 195,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Invoice',
        'implementingClassName' => 'Stripe\\Invoice',
        'currentClassName' => 'Stripe\\Invoice',
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
                'startLine' => 209,
                'endLine' => 209,
                'startTokenPos' => 417,
                'startFilePos' => 27137,
                'endTokenPos' => 417,
                'endFilePos' => 27140,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 209,
            'endLine' => 209,
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
                'startLine' => 209,
                'endLine' => 209,
                'startTokenPos' => 424,
                'startFilePos' => 27151,
                'endTokenPos' => 424,
                'endFilePos' => 27154,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 209,
            'endLine' => 209,
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
 * You can list all invoices, or list the invoices for a specific customer. The
 * invoices are returned sorted by creation date, with the most recently created
 * invoices appearing first.
 *
 * @param null|array{collection_method?: string, created?: array|int, customer?: string, due_date?: array|int, ending_before?: string, expand?: string[], limit?: int, starting_after?: string, status?: string, subscription?: string} $params
 * @param null|array|string $opts
 *
 * @return Collection<Invoice> of ApiResources
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 209,
        'endLine' => 214,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Invoice',
        'implementingClassName' => 'Stripe\\Invoice',
        'currentClassName' => 'Stripe\\Invoice',
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
            'startLine' => 226,
            'endLine' => 226,
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
                'startLine' => 226,
                'endLine' => 226,
                'startTokenPos' => 480,
                'startFilePos' => 27657,
                'endTokenPos' => 480,
                'endFilePos' => 27660,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 226,
            'endLine' => 226,
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
 * Retrieves the invoice with the given ID.
 *
 * @param array|string $id the ID of the API resource to retrieve, or an options array containing an `id` key
 * @param null|array|string $opts
 *
 * @return Invoice
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 226,
        'endLine' => 233,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Invoice',
        'implementingClassName' => 'Stripe\\Invoice',
        'currentClassName' => 'Stripe\\Invoice',
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
            'startLine' => 253,
            'endLine' => 253,
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
                'startLine' => 253,
                'endLine' => 253,
                'startTokenPos' => 543,
                'startFilePos' => 31070,
                'endTokenPos' => 543,
                'endFilePos' => 31073,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 253,
            'endLine' => 253,
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
                'startLine' => 253,
                'endLine' => 253,
                'startTokenPos' => 550,
                'startFilePos' => 31084,
                'endTokenPos' => 550,
                'endFilePos' => 31087,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 253,
            'endLine' => 253,
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
 * Draft invoices are fully editable. Once an invoice is <a
 * href="/docs/billing/invoices/workflow#finalized">finalized</a>, monetary values,
 * as well as <code>collection_method</code>, become uneditable.
 *
 * If you would like to stop the Stripe Billing engine from automatically
 * finalizing, reattempting payments on, sending reminders for, or <a
 * href="/docs/billing/invoices/reconciliation">automatically reconciling</a>
 * invoices, pass <code>auto_advance=false</code>.
 *
 * @param string $id the ID of the resource to update
 * @param null|array{account_tax_ids?: null|string[], application_fee_amount?: int, auto_advance?: bool, automatic_tax?: array{enabled: bool, liability?: array{account?: string, type: string}}, automatically_finalizes_at?: int, collection_method?: string, custom_fields?: null|array{name: string, value: string}[], days_until_due?: int, default_payment_method?: string, default_source?: null|string, default_tax_rates?: null|string[], description?: string, discounts?: null|array{coupon?: string, discount?: string, promotion_code?: string}[], due_date?: int, effective_at?: null|int, expand?: string[], footer?: string, issuer?: array{account?: string, type: string}, metadata?: null|array<string, string>, number?: null|string, on_behalf_of?: null|string, payment_settings?: array{default_mandate?: null|string, payment_method_options?: array{acss_debit?: null|array{mandate_options?: array{transaction_type?: string}, verification_method?: string}, bancontact?: null|array{preferred_language?: string}, card?: null|array{installments?: array{enabled?: bool, plan?: null|array{count?: int, interval?: string, type: string}}, request_three_d_secure?: string}, customer_balance?: null|array{bank_transfer?: array{eu_bank_transfer?: array{country: string}, type?: string}, funding_type?: string}, konbini?: null|array{}, sepa_debit?: null|array{}, us_bank_account?: null|array{financial_connections?: array{filters?: array{account_subcategories?: string[]}, permissions?: string[], prefetch?: string[]}, verification_method?: string}}, payment_method_types?: null|string[]}, rendering?: array{amount_tax_display?: null|string, pdf?: array{page_size?: string}, template?: string, template_version?: null|int}, shipping_cost?: null|array{shipping_rate?: string, shipping_rate_data?: array{delivery_estimate?: array{maximum?: array{unit: string, value: int}, minimum?: array{unit: string, value: int}}, display_name: string, fixed_amount?: array{amount: int, currency: string, currency_options?: array<string, array{amount: int, tax_behavior?: string}>}, metadata?: array<string, string>, tax_behavior?: string, tax_code?: string, type?: string}}, shipping_details?: null|array{address: array{city?: string, country?: string, line1?: string, line2?: string, postal_code?: string, state?: string}, name: string, phone?: null|string}, statement_descriptor?: string, transfer_data?: null|array{amount?: int, destination: string}} $params
 * @param null|array|string $opts
 *
 * @return Invoice the updated resource
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 253,
        'endLine' => 263,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Invoice',
        'implementingClassName' => 'Stripe\\Invoice',
        'currentClassName' => 'Stripe\\Invoice',
        'aliasName' => NULL,
      ),
      'addLines' => 
      array (
        'name' => 'addLines',
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
                'startLine' => 276,
                'endLine' => 276,
                'startTokenPos' => 664,
                'startFilePos' => 31769,
                'endTokenPos' => 664,
                'endFilePos' => 31772,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 276,
            'endLine' => 276,
            'startColumn' => 30,
            'endColumn' => 43,
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
                'startLine' => 276,
                'endLine' => 276,
                'startTokenPos' => 671,
                'startFilePos' => 31783,
                'endTokenPos' => 671,
                'endFilePos' => 31786,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 276,
            'endLine' => 276,
            'startColumn' => 46,
            'endColumn' => 57,
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
 * @return Invoice the added invoice
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 276,
        'endLine' => 283,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Invoice',
        'implementingClassName' => 'Stripe\\Invoice',
        'currentClassName' => 'Stripe\\Invoice',
        'aliasName' => NULL,
      ),
      'attachPayment' => 
      array (
        'name' => 'attachPayment',
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
                'startLine' => 293,
                'endLine' => 293,
                'startTokenPos' => 748,
                'startFilePos' => 32256,
                'endTokenPos' => 748,
                'endFilePos' => 32259,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 293,
            'endLine' => 293,
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
                'startLine' => 293,
                'endLine' => 293,
                'startTokenPos' => 755,
                'startFilePos' => 32270,
                'endTokenPos' => 755,
                'endFilePos' => 32273,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 293,
            'endLine' => 293,
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
 * @return Invoice the attached invoice
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 293,
        'endLine' => 300,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Invoice',
        'implementingClassName' => 'Stripe\\Invoice',
        'currentClassName' => 'Stripe\\Invoice',
        'aliasName' => NULL,
      ),
      'createPreview' => 
      array (
        'name' => 'createPreview',
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
                'startLine' => 310,
                'endLine' => 310,
                'startTokenPos' => 834,
                'startFilePos' => 32754,
                'endTokenPos' => 834,
                'endFilePos' => 32757,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 310,
            'endLine' => 310,
            'startColumn' => 42,
            'endColumn' => 55,
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
                'startLine' => 310,
                'endLine' => 310,
                'startTokenPos' => 841,
                'startFilePos' => 32768,
                'endTokenPos' => 841,
                'endFilePos' => 32771,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 310,
            'endLine' => 310,
            'startColumn' => 58,
            'endColumn' => 69,
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
 * @return Invoice the created invoice
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 310,
        'endLine' => 318,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Invoice',
        'implementingClassName' => 'Stripe\\Invoice',
        'currentClassName' => 'Stripe\\Invoice',
        'aliasName' => NULL,
      ),
      'finalizeInvoice' => 
      array (
        'name' => 'finalizeInvoice',
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
                'startLine' => 328,
                'endLine' => 328,
                'startTokenPos' => 932,
                'startFilePos' => 33322,
                'endTokenPos' => 932,
                'endFilePos' => 33325,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 328,
            'endLine' => 328,
            'startColumn' => 37,
            'endColumn' => 50,
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
                'startLine' => 328,
                'endLine' => 328,
                'startTokenPos' => 939,
                'startFilePos' => 33336,
                'endTokenPos' => 939,
                'endFilePos' => 33339,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 328,
            'endLine' => 328,
            'startColumn' => 53,
            'endColumn' => 64,
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
 * @return Invoice the finalized invoice
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 328,
        'endLine' => 335,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Invoice',
        'implementingClassName' => 'Stripe\\Invoice',
        'currentClassName' => 'Stripe\\Invoice',
        'aliasName' => NULL,
      ),
      'markUncollectible' => 
      array (
        'name' => 'markUncollectible',
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
                'startLine' => 345,
                'endLine' => 345,
                'startTokenPos' => 1016,
                'startFilePos' => 33817,
                'endTokenPos' => 1016,
                'endFilePos' => 33820,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 345,
            'endLine' => 345,
            'startColumn' => 39,
            'endColumn' => 52,
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
                'startLine' => 345,
                'endLine' => 345,
                'startTokenPos' => 1023,
                'startFilePos' => 33831,
                'endTokenPos' => 1023,
                'endFilePos' => 33834,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 345,
            'endLine' => 345,
            'startColumn' => 55,
            'endColumn' => 66,
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
 * @return Invoice the uncollectible invoice
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 345,
        'endLine' => 352,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Invoice',
        'implementingClassName' => 'Stripe\\Invoice',
        'currentClassName' => 'Stripe\\Invoice',
        'aliasName' => NULL,
      ),
      'pay' => 
      array (
        'name' => 'pay',
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
                'startLine' => 362,
                'endLine' => 362,
                'startTokenPos' => 1100,
                'startFilePos' => 34299,
                'endTokenPos' => 1100,
                'endFilePos' => 34302,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 362,
            'endLine' => 362,
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
                'startLine' => 362,
                'endLine' => 362,
                'startTokenPos' => 1107,
                'startFilePos' => 34313,
                'endTokenPos' => 1107,
                'endFilePos' => 34316,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 362,
            'endLine' => 362,
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
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return Invoice the paid invoice
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 362,
        'endLine' => 369,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Invoice',
        'implementingClassName' => 'Stripe\\Invoice',
        'currentClassName' => 'Stripe\\Invoice',
        'aliasName' => NULL,
      ),
      'removeLines' => 
      array (
        'name' => 'removeLines',
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
                'startLine' => 379,
                'endLine' => 379,
                'startTokenPos' => 1184,
                'startFilePos' => 34777,
                'endTokenPos' => 1184,
                'endFilePos' => 34780,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 379,
            'endLine' => 379,
            'startColumn' => 33,
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
                'startLine' => 379,
                'endLine' => 379,
                'startTokenPos' => 1191,
                'startFilePos' => 34791,
                'endTokenPos' => 1191,
                'endFilePos' => 34794,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 379,
            'endLine' => 379,
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
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return Invoice the removed invoice
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 379,
        'endLine' => 386,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Invoice',
        'implementingClassName' => 'Stripe\\Invoice',
        'currentClassName' => 'Stripe\\Invoice',
        'aliasName' => NULL,
      ),
      'sendInvoice' => 
      array (
        'name' => 'sendInvoice',
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
                'startLine' => 396,
                'endLine' => 396,
                'startTokenPos' => 1268,
                'startFilePos' => 35261,
                'endTokenPos' => 1268,
                'endFilePos' => 35264,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 396,
            'endLine' => 396,
            'startColumn' => 33,
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
                'startLine' => 396,
                'endLine' => 396,
                'startTokenPos' => 1275,
                'startFilePos' => 35275,
                'endTokenPos' => 1275,
                'endFilePos' => 35278,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 396,
            'endLine' => 396,
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
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return Invoice the sent invoice
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 396,
        'endLine' => 403,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Invoice',
        'implementingClassName' => 'Stripe\\Invoice',
        'currentClassName' => 'Stripe\\Invoice',
        'aliasName' => NULL,
      ),
      'updateLines' => 
      array (
        'name' => 'updateLines',
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
                'startLine' => 413,
                'endLine' => 413,
                'startTokenPos' => 1352,
                'startFilePos' => 35740,
                'endTokenPos' => 1352,
                'endFilePos' => 35743,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 413,
            'endLine' => 413,
            'startColumn' => 33,
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
                'startLine' => 413,
                'endLine' => 413,
                'startTokenPos' => 1359,
                'startFilePos' => 35754,
                'endTokenPos' => 1359,
                'endFilePos' => 35757,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 413,
            'endLine' => 413,
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
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return Invoice the updated invoice
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 413,
        'endLine' => 420,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Invoice',
        'implementingClassName' => 'Stripe\\Invoice',
        'currentClassName' => 'Stripe\\Invoice',
        'aliasName' => NULL,
      ),
      'voidInvoice' => 
      array (
        'name' => 'voidInvoice',
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
                'startLine' => 430,
                'endLine' => 430,
                'startTokenPos' => 1436,
                'startFilePos' => 36226,
                'endTokenPos' => 1436,
                'endFilePos' => 36229,
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
            'startColumn' => 33,
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
                'startLine' => 430,
                'endLine' => 430,
                'startTokenPos' => 1443,
                'startFilePos' => 36240,
                'endTokenPos' => 1443,
                'endFilePos' => 36243,
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
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return Invoice the voided invoice
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 430,
        'endLine' => 437,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Invoice',
        'implementingClassName' => 'Stripe\\Invoice',
        'currentClassName' => 'Stripe\\Invoice',
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
                'startLine' => 447,
                'endLine' => 447,
                'startTokenPos' => 1522,
                'startFilePos' => 36728,
                'endTokenPos' => 1522,
                'endFilePos' => 36731,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 447,
            'endLine' => 447,
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
                'startLine' => 447,
                'endLine' => 447,
                'startTokenPos' => 1529,
                'startFilePos' => 36742,
                'endTokenPos' => 1529,
                'endFilePos' => 36745,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 447,
            'endLine' => 447,
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
 * @return SearchResult<Invoice> the invoice search results
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 447,
        'endLine' => 452,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Invoice',
        'implementingClassName' => 'Stripe\\Invoice',
        'currentClassName' => 'Stripe\\Invoice',
        'aliasName' => NULL,
      ),
      'allLines' => 
      array (
        'name' => 'allLines',
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
            'startLine' => 465,
            'endLine' => 465,
            'startColumn' => 37,
            'endColumn' => 39,
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
                'startLine' => 465,
                'endLine' => 465,
                'startTokenPos' => 1590,
                'startFilePos' => 37295,
                'endTokenPos' => 1590,
                'endFilePos' => 37298,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 465,
            'endLine' => 465,
            'startColumn' => 42,
            'endColumn' => 55,
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
                'startLine' => 465,
                'endLine' => 465,
                'startTokenPos' => 1597,
                'startFilePos' => 37309,
                'endTokenPos' => 1597,
                'endFilePos' => 37312,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 465,
            'endLine' => 465,
            'startColumn' => 58,
            'endColumn' => 69,
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
 * @param string $id the ID of the invoice on which to retrieve the invoice line items
 * @param null|array $params
 * @param null|array|string $opts
 *
 * @return Collection<InvoiceLineItem> the list of invoice line items
 *
 * @throws Exception\\ApiErrorException if the request fails
 */',
        'startLine' => 465,
        'endLine' => 468,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Invoice',
        'implementingClassName' => 'Stripe\\Invoice',
        'currentClassName' => 'Stripe\\Invoice',
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