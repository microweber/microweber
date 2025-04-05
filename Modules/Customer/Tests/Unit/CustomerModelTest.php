<?php

namespace Modules\Customer\Tests\Unit;

use Illuminate\Support\Facades\DB;
use MicroweberPackages\Core\tests\TestCase;
use Modules\Customer\Models\Address;
use Modules\Customer\Models\Customer;

class CustomerModelTest extends TestCase
{
    public function testCustomerCreation()
    {
        DB::table('addresses')->truncate();
        DB::table('customers')->truncate();
        // Create a new customer
        $customer = Customer::create([
            'name' => 'John Doe',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'phone' => '1234567890',
            'email' => 'john.doe@example.com',
            'active' => 1,
            'user_id' => 1,
            'currency_id' => 1,
            'company_id' => 1
        ]);

        // Assert the customer was created
        $this->assertNotNull($customer);
        $this->assertEquals('John Doe', $customer->name);
        $this->assertEquals('john.doe@example.com', $customer->email);

        // Create an address for the customer
        $address = Address::create([
            'name' => 'Home',
            'address_street_1' => '123 Main St',
            'city' => 'Anytown',
            'state' => 'Anystate',
            'country_id' => 1,
            'zip' => '12345',
            'phone' => '1234567890',
            'type' => Address::BILLING_TYPE,
            'customer_id' => $customer->id
        ]);

        // Assert the address was created
        $this->assertNotNull($address);
        $this->assertEquals('123 Main St', $address->address_street_1);

        // Check the relationship
        $this->assertEquals($customer->id, $address->customer->id);
        $this->assertEquals('123 Main St', $customer->billingAddress->address_street_1);


        //delete
        $customer->delete();

        //check if deleted
        $customer = Customer::find($customer->id);
        $this->assertNull($customer);
    }

    public function testAddressRelationships()
    {
        DB::table('addresses')->truncate();
        DB::table('customers')->truncate();
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


    public function testGetFullName()
    {
        DB::table('addresses')->truncate();
        DB::table('customers')->truncate();
        $customer = Customer::create([
            'name' => 'Full Name',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'fullname@test.com'
        ]);

        $this->assertEquals('John Doe', $customer->getFullName());
    }

    public function testCompanyRelationship()
    {
        DB::table('addresses')->truncate();
        DB::table('customers')->truncate();
        $customer = Customer::create([
            'name' => 'Company Customer',
            'email' => 'company@test.com',
            'company_id' => 1
        ]);

        $this->assertEquals(1, $customer->company_id);
    }


    public function testJsonFieldOperations()
    {
        DB::table('addresses')->truncate();
        DB::table('customers')->truncate();
        // Test empty/null customer_data
        $customer1 = (new Customer())->create(['customer_data' => null]);
        $this->assertNull($customer1->customer_data);

        // Test multiple data fields
        $customer2 = (new Customer())->create([
            'customer_data' => [
                'is_premium' => true,
                'preferred_language' => 'en',
                'loyalty_points' => 100
            ]
        ]);
        $this->assertTrue($customer2->customer_data['is_premium']);
        $this->assertEquals('en', $customer2->customer_data['preferred_language']);
        $this->assertEquals(100, $customer2->customer_data['loyalty_points']);

        // Test attribute accessors
        $customer2->save();
        $this->assertTrue($customer2->fresh()->customer_data['is_premium']);

        // Test mass assignment protection
        $customer3 = (new Customer)->create();
        $customer3->update([
            'customer_data' => [
                'is_premium' => false,
                'loyalty_points' => 200
            ]
        ]);
        $refreshed = $customer3->fresh();
        $this->assertEquals(200, $refreshed->customer_data['loyalty_points']);

        // Test JSON field querying
        $premiumCount = Customer::where('customer_data->is_premium', true)->count();
        $this->assertEquals(1, $premiumCount);
    }
}
