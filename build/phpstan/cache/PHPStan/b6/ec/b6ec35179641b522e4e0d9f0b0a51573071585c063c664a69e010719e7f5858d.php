<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/Discount.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Stripe\Discount
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-28ea77655ad06bbfef1b8b6ac800d777a712a14d5bd738949bd4bbc494eba926-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Stripe\\Discount',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/Discount.php',
      ),
    ),
    'namespace' => 'Stripe',
    'name' => 'Stripe\\Discount',
    'shortName' => 'Discount',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A discount represents the actual application of a <a href="https://stripe.com/docs/api#coupons">coupon</a> or <a href="https://stripe.com/docs/api#promotion_codes">promotion code</a>.
 * It contains information about when the discount began, when it will end, and what it is applied to.
 *
 * Related guide: <a href="https://stripe.com/docs/billing/subscriptions/discounts">Applying discounts to subscriptions</a>
 *
 * @property string $id The ID of the discount object. Discounts cannot be fetched by ID. Use <code>expand[]=discounts</code> in API calls to expand discount IDs in an array.
 * @property string $object String representing the object\'s type. Objects of the same type share the same value.
 * @property null|string $checkout_session The Checkout session that this coupon is applied to, if it is applied to a particular session in payment mode. Will not be present for subscription mode.
 * @property Coupon $coupon A coupon contains information about a percent-off or amount-off discount you might want to apply to a customer. Coupons may be applied to <a href="https://stripe.com/docs/api#subscriptions">subscriptions</a>, <a href="https://stripe.com/docs/api#invoices">invoices</a>, <a href="https://stripe.com/docs/api/checkout/sessions">checkout sessions</a>, <a href="https://stripe.com/docs/api#quotes">quotes</a>, and more. Coupons do not work with conventional one-off <a href="https://stripe.com/docs/api#create_charge">charges</a> or <a href="https://stripe.com/docs/api/payment_intents">payment intents</a>.
 * @property null|Customer|string $customer The ID of the customer associated with this discount.
 * @property null|int $end If the coupon has a duration of <code>repeating</code>, the date that this discount will end. If the coupon has a duration of <code>once</code> or <code>forever</code>, this attribute will be null.
 * @property null|string $invoice The invoice that the discount\'s coupon was applied to, if it was applied directly to a particular invoice.
 * @property null|string $invoice_item The invoice item <code>id</code> (or invoice line item <code>id</code> for invoice line items of type=\'subscription\') that the discount\'s coupon was applied to, if it was applied directly to a particular invoice item or invoice line item.
 * @property null|PromotionCode|string $promotion_code The promotion code applied to create this discount.
 * @property int $start Date that the coupon was applied.
 * @property null|string $subscription The subscription that this coupon is applied to, if it is applied to a particular subscription.
 * @property null|string $subscription_item The subscription item that this coupon is applied to, if it is applied to a particular subscription item.
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 26,
    'endLine' => 29,
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
        'declaringClassName' => 'Stripe\\Discount',
        'implementingClassName' => 'Stripe\\Discount',
        'name' => 'OBJECT_NAME',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'discount\'',
          'attributes' => 
          array (
            'startLine' => 28,
            'endLine' => 28,
            'startTokenPos' => 27,
            'startFilePos' => 2866,
            'endTokenPos' => 27,
            'endFilePos' => 2875,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 28,
        'endLine' => 28,
        'startColumn' => 5,
        'endColumn' => 35,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
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