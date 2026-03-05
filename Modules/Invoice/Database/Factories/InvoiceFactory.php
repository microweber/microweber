<?php

namespace Modules\Invoice\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Invoice\Models\Invoice;
use Modules\Customer\Models\Customer;
use Illuminate\Support\Str;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        $invoiceNumber = 'INV-' . $this->faker->unique()->numberBetween(1000, 99999);

        return [
            'invoice_number' => $invoiceNumber,
            'reference_number' => $this->faker->optional()->regexify('[A-Z]{3}-[0-9]{4}'),
            'invoice_date' => $this->faker->date(),
            'due_date' => $this->faker->dateTimeBetween('+7 days', '+30 days')->format('Y-m-d'),
            'customer_id' => Customer::factory(),
            'company_id' => null,
            'invoice_template_id' => null,
            'status' => $this->faker->randomElement([
                Invoice::STATUS_DRAFT,
                Invoice::STATUS_SENT,
                Invoice::STATUS_VIEWED,
                Invoice::STATUS_PAID,
                Invoice::STATUS_COMPLETED,
            ]),
            'paid_status' => $this->faker->randomElement([
                Invoice::STATUS_UNPAID,
                Invoice::STATUS_PARTIALLY_PAID,
                Invoice::STATUS_PAID,
            ]),
            'sub_total' => $this->faker->numberBetween(10000, 100000),
            'discount' => 0,
            'discount_type' => 'fixed',
            'discount_val' => 0,
            'total' => $this->faker->numberBetween(10000, 100000),
            'due_amount' => $this->faker->numberBetween(0, 50000),
            'tax' => $this->faker->numberBetween(0, 2000),
            'notes' => $this->faker->optional()->sentence(),
            'unique_hash' => Str::uuid()->toString(),
            'tax_per_item' => false,
            'discount_per_item' => false,
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Invoice::STATUS_DRAFT,
            'paid_status' => Invoice::STATUS_UNPAID,
        ]);
    }

    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Invoice::STATUS_PAID,
            'paid_status' => Invoice::STATUS_PAID,
            'due_amount' => 0,
        ]);
    }

    public function overdue(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Invoice::STATUS_OVERDUE,
            'due_date' => now()->subDays(7)->format('Y-m-d'),
            'paid_status' => Invoice::STATUS_UNPAID,
        ]);
    }

    public function withCustomer(Customer $customer): static
    {
        return $this->state(fn (array $attributes) => [
            'customer_id' => $customer->id,
        ]);
    }
}
