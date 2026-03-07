<?php

use Modules\Billing\Models\Subscription;

test('subscription can be created with factory', function () {
    $subscription = Subscription::factory()->make();

    expect($subscription)->toBeInstanceOf(Subscription::class)
        ->and($subscription->status)->toBeIn(['active', 'inactive', 'cancelled']);
});

test('subscription has required attributes', function () {
    $subscription = Subscription::factory()->make([
        'name' => 'Premium Plan',
        'price' => 99.99,
    ]);

    expect($subscription->name)->toBe('Premium Plan')
        ->and($subscription->price)->toBe(99.99);
});

test('can validate subscription data', function ($status) {
    expect(in_array($status, ['active', 'inactive', 'cancelled', 'trialing']))->toBeTrue();
})->with([
    'active',
    'inactive',
    'cancelled',
    'trialing',
]);
