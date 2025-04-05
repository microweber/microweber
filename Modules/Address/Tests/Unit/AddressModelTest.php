<?php

namespace Modules\Address\Tests\Unit;

use Illuminate\Support\Facades\DB;
use MicroweberPackages\Core\tests\TestCase;
use Modules\Address\Models\Address;
use Modules\Customer\Models\Customer;

class AddressModelTest extends TestCase
{
    public function testAddressTypesAndRelationships()
    {
        // Ensure clean test environment
        DB::table('addresses')->truncate();
        DB::table('customers')->truncate();

        $customer = Customer::create([
            'name' => 'Address Test Customer',
            'email' => 'address.test@example.com'
        ]);
        $initialAddressCount = $customer->addresses()->count();
        $this->assertEquals(0, $initialAddressCount, 'Customer should have no addresses initially');

        // Create billing address
        $billingAddress = Address::create([
            'customer_id' => $customer->id,
            'type' => Address::BILLING_TYPE,
            'address_street_1' => '123 Billing St'
        ]);

        // Create shipping address
        $shippingAddress = Address::create([
            'customer_id' => $customer->id,
            'type' => Address::SHIPPING_TYPE,
            'address_street_1' => '456 Shipping Ave'
        ]);


        // Test relationships
        $this->assertEquals(2, $customer->addresses->count());
        $this->assertEquals($customer->id, $billingAddress->customer->id);
        /*
         * @var Address $billingAddress;
         */
        // Test address type methods
        $this->assertTrue($billingAddress->isBilling());
        $this->assertFalse($billingAddress->isShipping());
        $this->assertTrue($shippingAddress->isShipping());
        $this->assertFalse($shippingAddress->isBilling());

        // Test customer address relationships
        $this->assertEquals('123 Billing St', $customer->billingAddress->address_street_1);
        $this->assertEquals('456 Shipping Ave', $customer->shippingAddress->address_street_1);
    }
}
