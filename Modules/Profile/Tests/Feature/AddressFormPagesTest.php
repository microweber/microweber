<?php

namespace Modules\Profile\Tests\Feature;

use Filament\Facades\Filament;
use Livewire\Livewire;
use Modules\Address\Models\Address;
use Modules\Customer\Models\Customer;
use Modules\Profile\Filament\Pages\CreateAddress;
use Modules\Profile\Filament\Pages\EditAddress;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use MicroweberPackages\User\Models\User;

/**
 * The Add/Edit Address flows were moved off the (tall, overflowing) table modal
 * onto dedicated CreateAddress / EditAddress pages. These tests pin that:
 *  - the create page saves an address against the current customer and redirects;
 *  - the edit page prefills + updates an OWNED address and rejects others (404);
 *  - the Addresses table links to the dedicated pages, not a modal form.
 */
class AddressFormPagesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Filament::setCurrentPanel(Filament::getPanel('profile'));
    }

    private function countryId(): int
    {
        $country = \DB::table('countries')->first();
        if ($country) {
            return (int) $country->id;
        }

        return (int) \DB::table('countries')->insertGetId([
            'name' => 'United States',
            'code' => 'US',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function userWithCustomer(): array
    {
        $user = User::factory()->create([
            'email' => 'addr' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
        ]);
        $customer = Customer::factory()->create([
            'user_id' => $user->id,
            'email' => $user->email,
        ]);

        return [$user, $customer];
    }

    #[Test]
    public function create_address_page_saves_against_the_current_customer(): void
    {
        [$user, $customer] = $this->userWithCustomer();
        $this->actingAs($user);

        Livewire::test(CreateAddress::class)
            ->set('data.name', 'Home')
            ->set('data.type', Address::SHIPPING_TYPE)
            ->set('data.address_street_1', '1 Test Street')
            ->set('data.city', 'Testville')
            ->set('data.zip', '12345')
            ->set('data.country_id', $this->countryId())
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('addresses', [
            'rel_type' => 'customer',
            'rel_id' => $customer->id,
            'name' => 'Home',
            'city' => 'Testville',
        ]);
    }

    #[Test]
    public function edit_address_page_prefills_and_updates_an_owned_address(): void
    {
        [$user, $customer] = $this->userWithCustomer();
        $this->actingAs($user);

        $address = Address::create([
            'rel_type' => 'customer',
            'rel_id' => $customer->id,
            'name' => 'Old Label',
            'type' => Address::SHIPPING_TYPE,
            'address_street_1' => '1 Old Street',
            'city' => 'Oldtown',
            'zip' => '00000',
            'country_id' => $this->countryId(),
        ]);

        Livewire::withQueryParams(['address' => $address->id])
            ->test(EditAddress::class)
            ->assertSet('data.name', 'Old Label')
            ->set('data.name', 'New Label')
            ->set('data.city', 'Newtown')
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect();

        $address->refresh();
        $this->assertEquals('New Label', $address->name);
        $this->assertEquals('Newtown', $address->city);
    }

    #[Test]
    public function edit_address_page_rejects_an_address_the_user_does_not_own(): void
    {
        [$owner, $ownerCustomer] = $this->userWithCustomer();
        [$attacker] = $this->userWithCustomer();

        $address = Address::create([
            'rel_type' => 'customer',
            'rel_id' => $ownerCustomer->id,
            'name' => 'Private',
            'type' => Address::SHIPPING_TYPE,
            'address_street_1' => '1 Private Street',
            'city' => 'Secret',
            'zip' => '11111',
            'country_id' => $this->countryId(),
        ]);

        // Hitting the edit route for someone else's address must 404 (the Edit
        // link is a GET; mount() aborts when resolveOwnedAddress returns null).
        $this->actingAs($attacker)
            ->get('/profile/edit-address?address=' . $address->id)
            ->assertNotFound();
    }

    #[Test]
    public function addresses_table_links_to_the_dedicated_create_page_not_a_modal(): void
    {
        [$user] = $this->userWithCustomer();

        $response = $this->actingAs($user)
            ->get('/profile/saved-addresses')
            ->assertOk();

        // The "Add" buttons are now links to the dedicated create page.
        $response->assertSee('/profile/create-address');
    }
}
