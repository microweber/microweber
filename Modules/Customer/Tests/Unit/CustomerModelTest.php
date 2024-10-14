<?php

namespace Modules\Customer\Tests\Unit;

use MicroweberPackages\Core\tests\TestCase;
use Modules\Customer\Models\Address;
use Modules\Customer\Models\Customer;

class CustomerModelTest extends TestCase
{
    public function testCustomerCreation()
    {
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
}
