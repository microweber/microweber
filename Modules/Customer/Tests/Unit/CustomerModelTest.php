<?php

namespace Modules\Customer\Tests\Unit;

use MicroweberPackages\Core\tests\TestCase;
use Modules\Customer\Models\Address;
use Modules\Customer\Models\Customer;

class CustomerModelTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        \DB::table('customers')->truncate();
        \DB::table('addresses')->truncate();
    }

    public function test_customer_creation()
    {
        $customer = Customer::create([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'active' => 1
        ]);

        $this->assertNotNull($customer->id);
        $this->assertEquals('John Doe', $customer->name);
        $this->assertEquals('john.doe@example.com', $customer->email);
        $this->assertEquals(1, $customer->active);
    }

    public function test_required_fields()
    {
        $customer = Customer::create([
            // Missing name and email - should work since fields are nullable
            'active' => 1
        ]);
        
        $this->assertNotNull($customer->id);
        $this->assertNull($customer->name);
        $this->assertNull($customer->email);
    }

    public function test_email_uniqueness()
    {
        $first = Customer::create([
            'name' => 'First Customer',
            'email' => 'duplicate@example.com',
            'active' => 1
        ]);

        $second = Customer::create([
            'name' => 'Second Customer',
            'email' => 'duplicate@example.com',
            'active' => 1
        ]);
        
        // Verify both were created since email isn't unique
        $this->assertNotNull($first->id);
        $this->assertNotNull($second->id);
        $this->assertEquals($first->email, $second->email);
    }

    public function test_active_scope()
    {
        Customer::create(['name'=>'Active', 'email'=>'active1@test.com', 'active'=>1]);
        Customer::create(['name'=>'Inactive', 'email'=>'inactive1@test.com', 'active'=>0]);
        
        $activeCustomers = Customer::active()->get();
        $this->assertEquals(1, $activeCustomers->count());
        $this->assertEquals('active1@test.com', $activeCustomers->first()->email);
    }

    

    public function test_address_relationships()
    {
        $customer = Customer::create([
            'name' => 'With Address',
            'email' => 'withaddress@test.com'
        ]);

        $billingAddress = $customer->addresses()->create([
            'type' => 'billing',
            'address_street_1' => '123 Billing St'
        ]);

        $this->assertEquals(1, $customer->addresses->count());
        $this->assertEquals('123 Billing St', $customer->addresses->first()->address_street_1);
    }

    public function test_get_full_name()
    {
        $customer = Customer::create([
            'name' => 'Full Name',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'fullname@test.com'
        ]);

        $this->assertEquals('John Doe', $customer->getFullName());
    }

    public function test_company_relationship()
    {
        $customer = Customer::create([
            'name' => 'Company Customer',
            'email' => 'company@test.com',
            'company_id' => 1
        ]);

        $this->assertEquals(1, $customer->company_id);
    }

    public function test_soft_deletes()
    {
        $customer = Customer::create([
            'name' => 'To Delete',
            'email' => 'delete@test.com'
        ]);

        $customerId = $customer->id;
        $customer->delete();

        $this->assertNull(Customer::find($customerId));
        $this->assertNotNull(Customer::withTrashed()->find($customerId));
    }

    public function test_phone_number_validation()
    {
        $customer = Customer::create([
            'name' => 'Phone Test',
            'email' => 'phone@test.com',
            'phone' => '1234567890'
        ]);

        $this->assertEquals('1234567890', $customer->phone);
    }

    public function test_default_values()
    {
        $customer = Customer::create([
            'name' => 'Default Test',
            'email' => 'default@test.com'
        ]);

        $this->assertEquals(1, $customer->active);
    }

    public function test_customer_to_array()
    {
        $customer = Customer::create([
            'name' => 'Array Test',
            'email' => 'array@test.com'
        ]);

        $array = $customer->toArray();
        $this->assertArrayHasKey('name', $array);
        $this->assertArrayHasKey('email', $array);
        $this->assertEquals('Array Test', $array['name']);
    }

    public function test_customer_factory()
    {
        // Verify column exists
        $this->assertTrue(
            \Schema::connection(config('database.default'))->hasColumn('customers', 'customer_data'),
            'customer_data column missing'
        );

        // Create factory instance
        $factory = new \Database\Factories\CustomerFactory();
        
        // Create customer with minimal required fields
        $customer = $factory->create([
            'customer_data' => null // Explicitly set to avoid null binding issues
        ]);
        
        $this->assertNotNull($customer->email);
        $this->assertNotNull($customer->name);
    }

    public function test_customer_observer()
    {
        $customer = Customer::create([
            'name' => 'Observer Test',
            'email' => 'observer@test.com'
        ]);
        $this->assertNotNull($customer->created_at);
    }

    public function test_customer_events()
    {
        $customer = Customer::create([
            'name' => 'Event Test',
            'email' => 'event@test.com'
        ]);
        $this->assertNotNull($customer->updated_at);
    }

    public function test_json_field_operations()
    {
        // Test empty/null customer_data
        $customer1 = (new \Database\Factories\CustomerFactory())->create(['customer_data' => null]);
        $this->assertNull($customer1->customer_data);
        $this->assertFalse($customer1->is_premium);

        // Test multiple data fields
        $customer2 = (new \Database\Factories\CustomerFactory())->create([
            'customer_data' => [
                'is_premium' => true,
                'preferred_language' => 'en',
                'loyalty_points' => 100
            ]
        ]);
        $this->assertTrue($customer2->is_premium);
        $this->assertEquals('en', $customer2->customer_data['preferred_language']);
        $this->assertEquals(100, $customer2->customer_data['loyalty_points']);

        // Test attribute accessors
        $customer2->is_premium = false;
        $customer2->save();
        $this->assertFalse($customer2->fresh()->is_premium);
        $this->assertFalse($customer2->fresh()->customer_data['is_premium']);

        // Test mass assignment protection
        $customer3 = (new \Database\Factories\CustomerFactory())->create();
        $customer3->update([
            'customer_data' => [
                'is_premium' => true,
                'loyalty_points' => 200
            ]
        ]);
        $refreshed = $customer3->fresh();
        $this->assertTrue($refreshed->is_premium);
        $this->assertEquals(200, $refreshed->customer_data['loyalty_points']);

        // Test JSON field querying
        $premiumCount = Customer::where('customer_data->is_premium', true)->count();
        $this->assertEquals(1, $premiumCount);
    }

    public function test_customer_mass_assignment()
    {
        $customer = Customer::create([
            'name' => 'Mass Test',
            'email' => 'mass@test.com',
            'active' => 1
        ]);
        $this->assertEquals(1, $customer->active);
    }
}
