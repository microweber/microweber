<?php

namespace Modules\Customer\Tests\Filament;

use Livewire\Livewire;
use Modules\Customer\Filament\CustomerResource;
use Modules\Customer\Filament\CustomerResource\Pages\ListCustomers;
use Modules\Customer\Models\Customer;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\FilamentResourceTestCase;

class CustomerResourceTest extends FilamentResourceTestCase
{
    protected function getResourceClass(): string
    {
        return CustomerResource::class;
    }

    #[Test]
    public function it_can_render_list_page(): void
    {
        $this->actingAsAdmin();
        Livewire::test(ListCustomers::class)->assertSuccessful();
    }

    #[Test]
    public function it_resource_has_correct_model(): void
    {
        $this->assertEquals(Customer::class, CustomerResource::getModel());
    }
}
