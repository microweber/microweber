<?php

namespace Modules\Customer\Tests\Unit;

use MicroweberPackages\Core\tests\TestCase;
use Modules\Customer\Models\Customer;
use Modules\Customer\Models\ModelFilters\CustomerFilter;

class CustomerFilterTest extends TestCase
{
    

    public function testCustomerFilters()
    {
        // Ensure complete test isolation
        \DB::table('customers')->truncate();
        $initialCount = Customer::count();
        if ($initialCount > 0) {
            \Log::error("Customers table not empty after truncate! Found {$initialCount} records");
            \DB::statement('DELETE FROM customers');
            \DB::statement('DELETE FROM sqlite_sequence WHERE name="customers"');
        }
        $this->assertEquals(0, Customer::count(), 'Database should be empty before test');
        
        // Create test data

        // Create test customers
        $activeCustomer = Customer::create(['name' => 'Active Customer', 'email' => 'active@example.com', 'active' => 1]);
        Customer::create(['name' => 'Inactive Customer', 'email' => 'inactive@example.com', 'active' => 0]);
        Customer::create(['name' => 'Premium Customer', 'email' => 'premium@example.com', 'is_premium' => 1, 'active' => 0]);

        // Debug all customers
        error_log("=== ALL CUSTOMERS ===");
        error_log(print_r(Customer::all()->toArray(), true));
        
        // Test active filter
        $activeCustomers = Customer::filter(['active' => 1])->get();
        error_log("=== ACTIVE CUSTOMERS ===");
        error_log(print_r($activeCustomers->toArray(), true));
        $this->assertEquals(1, $activeCustomers->count());
        $this->assertEquals('active@example.com', $activeCustomers->first()->email);

        // Test inactive filter
        $inactiveCustomers = Customer::filter(['active' => 0])->get();
        $this->assertEquals(2, $inactiveCustomers->count());
        $inactiveEmails = $inactiveCustomers->pluck('email')->toArray();
        $this->assertContains('inactive@example.com', $inactiveEmails);
        $this->assertContains('premium@example.com', $inactiveEmails);

        // Debug premium customers
        error_log("=== PREMIUM CUSTOMER DATA ===");
        $premiumCustomer = Customer::where('email', 'premium@example.com')->first();
        error_log(print_r($premiumCustomer->toArray(), true));
        
        // Test premium filter
        $premiumCustomers = Customer::filter(['is_premium' => 1])->get();
        error_log("=== PREMIUM FILTER RESULTS ===");
        error_log(print_r($premiumCustomers->toArray(), true));
        $this->assertEquals(1, $premiumCustomers->count());
        $this->assertEquals('premium@example.com', $premiumCustomers->first()->email);

        // Test search filter
        $searchResults = Customer::filter(['search' => 'Premium'])->get();
        $this->assertGreaterThanOrEqual(1, $searchResults->count());
        $searchEmails = $searchResults->pluck('email')->toArray();
        $this->assertContains('premium@example.com', $searchEmails);
    }
}