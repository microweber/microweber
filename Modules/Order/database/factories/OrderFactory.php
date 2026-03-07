<?php

namespace Modules\Order\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Models\Order;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'order_reference_id' => 'ORDER-' . now()->format('YmdHis') . '-' . $this->faker->unique()->randomNumber(4),
            'email' => $this->faker->safeEmail(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'country' => $this->faker->country(),
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'zip' => $this->faker->postcode(),
            'address' => $this->faker->streetAddress(),
            'address2' => $this->faker->secondaryAddress(),
            'phone' => $this->faker->phoneNumber(),
            'other_info' => $this->faker->optional()->text(),
            'order_status' => $this->faker->randomElement([
                OrderStatus::New->value,
                OrderStatus::Processing->value,
                OrderStatus::Shipped->value,
                OrderStatus::Delivered->value,
                OrderStatus::Cancelled->value,
                OrderStatus::Refunded->value,
            ]),
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'currency' => $this->faker->randomElement(['USD', 'EUR', 'GBP']),
            'currency_code' => $this->faker->randomElement(['USD', 'EUR', 'GBP']),
            'order_completed' => $this->faker->boolean(),
            'is_paid' => $this->faker->boolean(),
            'customer_id' => null,
            'payment_provider_id' => null,
            'payment_provider' => $this->faker->optional()->word(),
            'shipping_provider_id' => null,
            'shipping_provider' => $this->faker->optional()->word(),
            'transaction_id' => $this->faker->optional()->uuid(),
            'payment_status' => $this->faker->optional()->word(),
            'shipping_amount' => $this->faker->optional()->randomFloat(2, 5, 50),
            'discount_value' => $this->faker->optional()->randomFloat(2, 0, 50),
            'taxes_amount' => $this->faker->optional()->randomFloat(2, 0, 20),
            'items_count' => $this->faker->numberBetween(1, 10),
            'custom_fields_data' => null,
            'payment_data' => null,
            'session_id' => $this->faker->optional()->uuid(),
            'user_ip' => $this->faker->optional()->ipv4(),
            'url' => $this->faker->optional()->url(),
            'promo_code' => $this->faker->optional()->word(),
            'skip_promo_code' => $this->faker->optional()->boolean(),
            'coupon_id' => null,
            'discount_type' => $this->faker->optional()->word(),
            'rel_id' => null,
            'rel_type' => null,
            'price' => $this->faker->optional()->randomFloat(2, 10, 1000),
        ];
    }

    /**
     * Configure the order with new status
     */
    public function withStatusNew(): static
    {
        return $this->state(fn (array $attributes) => [
            'order_status' => OrderStatus::New->value,
        ]);
    }

    /**
     * Configure the order as completed (delivered)
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'order_status' => OrderStatus::Delivered->value,
            'order_completed' => true,
            'is_paid' => true,
        ]);
    }

    /**
     * Configure the order as cancelled
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'order_status' => OrderStatus::Cancelled->value,
            'order_completed' => false,
        ]);
    }

    /**
     * Configure the order as paid
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_paid' => true,
        ]);
    }

    /**
     * Configure the order with a specific amount
     */
    public function withAmount(float $amount): static
    {
        return $this->state(fn (array $attributes) => [
            'amount' => $amount,
        ]);
    }

    /**
     * Configure the order with a specific customer
     */
    public function forCustomer(int $customerId): static
    {
        return $this->state(fn (array $attributes) => [
            'customer_id' => $customerId,
        ]);
    }
}
