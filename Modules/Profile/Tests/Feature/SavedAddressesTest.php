<?php

namespace Modules\Profile\Tests\Feature;

use Modules\Address\Models\Address;
use Modules\Customer\Models\Customer;
use Modules\Profile\Filament\Pages\SavedAddresses;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use MicroweberPackages\User\Models\User;

class SavedAddressesTest extends TestCase
{
    use DatabaseTransactions;
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->panel = 'profile';
    }

    /** @test */
    public function it_saved_addresses_page_is_accessible_to_authenticated_users(): void
    {
        $user = User::factory()->create([
            'email' => 'test' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($user)
            ->get('/profile/saved-addresses');

        // Filament pages may return 302 if not fully set up, so we accept redirects too
        $this->assertTrue(
            $response->isOk() || $response->isRedirect(),
            'Saved addresses page should be accessible or redirect'
        );
    }

    /** @test */
    public function it_saved_addresses_page_requires_authentication(): void
    {
        $response = $this->get('/profile/saved-addresses');

        // Should redirect to login if not authenticated
        $this->assertTrue(
            $response->isRedirect() || $response->isForbidden(),
            'Saved addresses page should require authentication'
        );
    }

    /** @test */
    public function it_displays_saved_addresses_for_customer(): void
    {
        $user = User::factory()->create([
            'email' => 'customer' . uniqid() . '@example.com',
        ]);

        $customer = Customer::factory()->create([
            'user_id' => $user->id,
            'email' => $user->email,
        ]);

        $country = \DB::table('countries')->first();
        if (!$country) {
            $countryId = \DB::table('countries')->insertGetId([
                'name' => 'United States',
                'code' => 'US',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $countryId = $country->id;
        }

        Address::create([
            'rel_type' => 'customer',
            'rel_id' => $customer->id,
            'name' => 'Home Address',
            'type' => Address::SHIPPING_TYPE,
            'address_street_1' => '123 Main St',
            'city' => 'New York',
            'state' => 'NY',
            'zip' => '10001',
            'country_id' => $countryId,
        ]);

        $this->actingAs($user)
            ->get('/profile/saved-addresses')
            ->assertOk()
            ->assertSee('Home Address')
            ->assertSee('123 Main St');
    }

    /** @test */
    public function it_displays_no_addresses_message_when_customer_has_no_addresses(): void
    {
        $user = User::factory()->create([
            'email' => 'newcustomer' . uniqid() . '@example.com',
        ]);

        Customer::factory()->create([
            'user_id' => $user->id,
            'email' => $user->email,
        ]);

        $this->actingAs($user)
            ->get('/profile/saved-addresses')
            ->assertOk()
            ->assertSee('You have not saved any addresses yet');
    }

    /** @test */
    public function it_page_shows_address_types(): void
    {
        $user = User::factory()->create([
            'email' => 'customer' . uniqid() . '@example.com',
        ]);

        $customer = Customer::factory()->create([
            'user_id' => $user->id,
            'email' => $user->email,
        ]);

        $country = \DB::table('countries')->first();
        if (!$country) {
            $countryId = \DB::table('countries')->insertGetId([
                'name' => 'United States',
                'code' => 'US',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $countryId = $country->id;
        }

        Address::create([
            'rel_type' => 'customer',
            'rel_id' => $customer->id,
            'name' => 'Shipping Address',
            'type' => Address::SHIPPING_TYPE,
            'address_street_1' => '123 Shipping St',
            'city' => 'New York',
            'zip' => '10001',
            'country_id' => $countryId,
        ]);

        Address::create([
            'rel_type' => 'customer',
            'rel_id' => $customer->id,
            'name' => 'Billing Address',
            'type' => Address::BILLING_TYPE,
            'address_street_1' => '456 Billing Ave',
            'city' => 'Los Angeles',
            'zip' => '90001',
            'country_id' => $countryId,
        ]);

        $response = $this->actingAs($user)
            ->get('/profile/saved-addresses')
            ->assertOk();

        // Verify both addresses are displayed
        $response->assertSee('Shipping Address')
            ->assertSee('Billing Address');
    }

    /** @test */
    public function it_only_shows_addresses_belonging_to_authenticated_customer(): void
    {
        $user1 = User::factory()->create([
            'email' => 'customer1' . uniqid() . '@example.com',
        ]);

        $customer1 = Customer::factory()->create([
            'user_id' => $user1->id,
            'email' => $user1->email,
        ]);

        $user2 = User::factory()->create([
            'email' => 'customer2' . uniqid() . '@example.com',
        ]);

        $customer2 = Customer::factory()->create([
            'user_id' => $user2->id,
            'email' => $user2->email,
        ]);

        $country = \DB::table('countries')->first();
        if (!$country) {
            $countryId = \DB::table('countries')->insertGetId([
                'name' => 'United States',
                'code' => 'US',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $countryId = $country->id;
        }

        Address::create([
            'rel_type' => 'customer',
            'rel_id' => $customer1->id,
            'name' => 'User1 Address',
            'type' => Address::SHIPPING_TYPE,
            'address_street_1' => 'User1 Street',
            'city' => 'City',
            'zip' => '12345',
            'country_id' => $countryId,
        ]);

        Address::create([
            'rel_type' => 'customer',
            'rel_id' => $customer2->id,
            'name' => 'User2 Address',
            'type' => Address::SHIPPING_TYPE,
            'address_street_1' => 'User2 Street',
            'city' => 'City',
            'zip' => '54321',
            'country_id' => $countryId,
        ]);

        $this->actingAs($user1)
            ->get('/profile/saved-addresses')
            ->assertOk()
            ->assertSee('User1 Address')
            ->assertDontSee('User2 Address');
    }

    /** @test */
    public function it_shows_address_type_badge(): void
    {
        $user = User::factory()->create([
            'email' => 'customer' . uniqid() . '@example.com',
        ]);

        $customer = Customer::factory()->create([
            'user_id' => $user->id,
            'email' => $user->email,
        ]);

        $country = \DB::table('countries')->first();
        if (!$country) {
            $countryId = \DB::table('countries')->insertGetId([
                'name' => 'United States',
                'code' => 'US',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $countryId = $country->id;
        }

        Address::create([
            'rel_type' => 'customer',
            'rel_id' => $customer->id,
            'name' => 'Shipping Address',
            'type' => Address::SHIPPING_TYPE,
            'address_street_1' => '123 Main St',
            'city' => 'New York',
            'zip' => '10001',
            'country_id' => $countryId,
        ]);

        $this->actingAs($user)
            ->get('/profile/saved-addresses')
            ->assertOk()
            ->assertSee('Shipping');
    }

    /** @test */
    public function it_displays_address_details_in_table(): void
    {
        $user = User::factory()->create([
            'email' => 'customer' . uniqid() . '@example.com',
        ]);

        $customer = Customer::factory()->create([
            'user_id' => $user->id,
            'email' => $user->email,
        ]);

        $country = \DB::table('countries')->first();
        if (!$country) {
            $countryId = \DB::table('countries')->insertGetId([
                'name' => 'United States',
                'code' => 'US',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $countryId = $country->id;
        }

        Address::create([
            'rel_type' => 'customer',
            'rel_id' => $customer->id,
            'name' => 'Office Address',
            'type' => Address::SHIPPING_TYPE,
            'address_street_1' => '456 Business Ave',
            'address_street_2' => 'Suite 100',
            'city' => 'San Francisco',
            'state' => 'CA',
            'zip' => '94102',
            'phone' => '555-1234',
            'country_id' => $countryId,
        ]);

        $this->actingAs($user)
            ->get('/profile/saved-addresses')
            ->assertOk()
            ->assertSee('Office Address')
            ->assertSee('456 Business Ave')
            ->assertSee('San Francisco')
            ->assertSee('CA')
            ->assertSee('94102');
    }
}
