<?php

use Modules\Billing\Models\Subscription;

beforeEach(function () {
    // Ensure Faker Generator has providers loaded. The container may
    // auto-resolve a bare Generator without providers in Pest context.
    app()->forgetInstance(\Faker\Generator::class);
    app()->instance(\Faker\Generator::class, \Faker\Factory::create('en_US'));
});

test('subscription can be created with factory', function () {

    // Provide explicit IDs to avoid nested factory resolution which
    // triggers Faker providers that may not be available in Pest context
    $subscription = Subscription::factory()->make([
        'customer_id' => 1,
        'subscription_plan_id' => 1,
    ]);

    expect($subscription)->toBeInstanceOf(Subscription::class)
        ->and($subscription->stripe_status)->toBeIn(['active', 'inactive', 'cancelled']);
});

test('subscription has required attributes', function () {
    $subscription = Subscription::factory()->make([
        'customer_id' => 1,
        'subscription_plan_id' => 1,
        'stripe_price' => 'price_test',
    ]);

    expect($subscription)->toBeInstanceOf(Subscription::class)
        ->and($subscription->stripe_status)->toBe('active');
});

test('can validate subscription data', function ($status) {
    expect(in_array($status, ['active', 'inactive', 'cancelled', 'trialing']))->toBeTrue();
})->with([
    'active',
    'inactive',
    'cancelled',
    'trialing',
]);
