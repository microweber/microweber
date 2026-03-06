<?php

namespace Modules\Customer\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;

use Tests\TestCase;
use Modules\Customer\Models\Customer;
use Modules\Customer\Models\ModelFilters\CustomerFilter;

class CustomerFilterTest extends TestCase
{


    #[Test]


    public function it_customer_filters(): void {
        // Ensure complete test isolation
        Customer::query()->delete();
        $this->assertEquals(0, Customer::count(), 'Database should be empty before test');

        $activeCustomer = (new Customer())->create([
            'status' => 'active',
            'email' => 'active@example.com'
        ]);
        $inactiveCustomer = (new  Customer())->create([
            'status'  => 'inactive',
            'email' => 'inactive@example.com'
        ]);
        $premiumCustomer = (new Customer)->create([
            'status'  => 'inactive',
            'email' => 'premium@example.com',
            'customer_data' => ['is_premium' => true]
        ]);



        // Test active filter
        $activeCustomers = Customer::active()->get();


        $this->assertEquals(1, $activeCustomers->count());
        $this->assertEquals('active@example.com', $activeCustomers->first()->email);

        // Test inactive filter
        $inactiveCustomers = Customer::inactive()->get();
        $this->assertEquals(2, $inactiveCustomers->count());
        $inactiveEmails = $inactiveCustomers->pluck('email')->toArray();
        $this->assertContains('inactive@example.com', $inactiveEmails);
        $this->assertContains('premium@example.com', $inactiveEmails);




        // Test active filter
        $activeCustomers = Customer::active()->get();
        $this->assertEquals(1, $activeCustomers->count());
        $this->assertEquals('active@example.com', $activeCustomers->first()->email);

        // Test customer data JSON field
        $this->assertEquals(
            ['is_premium' => true],
            $premiumCustomer->fresh()->customer_data
        );

        // Test search filter
        $searchResults = Customer::filter(['search' => $premiumCustomer->name])->get();
        $this->assertGreaterThanOrEqual(1, $searchResults->count());
        $this->assertContains($premiumCustomer->email, $searchResults->pluck('email')->toArray());
    }
}
