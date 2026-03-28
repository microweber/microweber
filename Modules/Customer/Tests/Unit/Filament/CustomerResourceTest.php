<?php

namespace Modules\Customer\Tests\Unit\Filament;

use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Modules\Customer\Filament\CustomerResource;
use Modules\Customer\Filament\CustomerResource\Pages\ManageCustomers;
use Modules\Customer\Models\Customer;
use Modules\Company\Models\Company;
use MicroweberPackages\User\Models\User;
use Modules\Currency\Models\Currency;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CustomerResourceTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
        DB::table('customers')->delete();
        DB::table('companies')->delete();
        DB::table('currencies')->delete();
    }

    protected function getResourceClass(): string
    {
        return CustomerResource::class;
    }

    #[Test]
    public function it_index_page_loads_without_errors(): void
    {
        Livewire::test(ManageCustomers::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_index_page_shows_all_records(): void
    {
        $customers = Customer::factory()->count(3)->create();

        Livewire::test(ManageCustomers::class)
            ->assertCanSeeTableRecords($customers);
    }

    #[Test]
    public function it_index_page_supports_pagination(): void
    {
        Customer::factory()->count(15)->create();

        Livewire::test(ManageCustomers::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_index_page_supports_search(): void
    {
        $customer = Customer::factory()->create([
            'name' => 'Test Search Customer',
            'email' => 'search@test.com',
        ]);

        Livewire::test(ManageCustomers::class)
            ->searchTable('Test Search')
            ->assertCanSeeTableRecords([$customer]);
    }

    #[Test]
    public function it_create_page_renders_form(): void
    {
        Livewire::test(ManageCustomers::class)
            ->assertSuccessful()
            ->assertActionExists('create');
    }

    #[Test]
    public function it_create_page_validates_required_fields(): void
    {
        Livewire::test(ManageCustomers::class)
            ->callAction('create', data: [
                'name' => '',
                'user_id' => '',
                'currency_id' => '',
            ])
            ->assertHasActionErrors(['name']);
    }

    #[Test]
    public function it_create_page_saves_new_record(): void
    {
        $user = User::factory()->create();
        $currency = Currency::factory()->create();

        Livewire::test(ManageCustomers::class)
            ->callAction('create', data: [
                'name' => 'Test Customer',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john@example.com',
                'phone' => '1234567890',
                'active' => true,
                'user_id' => $user->id,
                'currency_id' => $currency->id,
            ])
            ->assertHasNoActionErrors();

        $this->assertDatabaseHas('customers', [
            'name' => 'Test Customer',
            'email' => 'john@example.com',
            'user_id' => $user->id,
        ]);
    }

    #[Test]
    public function it_edit_page_pre_fills_form_data(): void
    {
        $customer = Customer::factory()->create([
            'name' => 'Edit Test Customer',
            'email' => 'edit@test.com',
        ]);

        Livewire::test(ManageCustomers::class)
            ->assertSuccessful()
            ->assertCanSeeTableRecords([$customer]);
    }

    #[Test]
    public function it_edit_page_updates_record(): void
    {
        $user = User::factory()->create();
        $currency = Currency::factory()->create();
        $customer = Customer::factory()->create([
            'name' => 'Original Name',
            'user_id' => $user->id,
            'currency_id' => $currency->id,
        ]);

        Livewire::test(ManageCustomers::class)
            ->callTableAction('edit', $customer, data: [
                'name' => 'Updated Name',
                'user_id' => $user->id,
                'currency_id' => $currency->id,
            ])
            ->assertHasNoTableActionErrors();

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'name' => 'Updated Name',
        ]);
    }

    #[Test]
    public function it_delete_action_removes_record(): void
    {
        $customer = Customer::factory()->create();

        Livewire::test(ManageCustomers::class)
            ->callTableAction('delete', $customer);

        $this->assertDatabaseMissing('customers', [
            'id' => $customer->id,
        ]);
    }

    #[Test]
    public function it_can_filter_by_active(): void
    {
        $activeCustomer = Customer::factory()->create(['active' => true]);
        $inactiveCustomer = Customer::factory()->create(['active' => false]);

        Livewire::test(ManageCustomers::class)
            ->filterTable('active')
            ->assertCanSeeTableRecords([$activeCustomer])
            ->assertCanNotSeeTableRecords([$inactiveCustomer]);
    }

    #[Test]
    public function it_customer_has_company_relationship(): void
    {
        $company = Company::factory()->create();
        $customer = Customer::factory()->create([
            'company_id' => $company->id,
        ]);

        $this->assertInstanceOf(Company::class, $customer->company);
        $this->assertEquals($company->id, $customer->company->id);
    }

    #[Test]
    public function it_global_search_returns_results(): void
    {
        $customer = Customer::factory()->create([
            'name' => 'Global Search Test',
            'email' => 'global@test.com',
        ]);

        $results = CustomerResource::getGlobalSearchResults('Global Search');
        $this->assertNotEmpty($results);
    }
}
