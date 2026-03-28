<?php

namespace Modules\Tax\Tests;

use PHPUnit\Framework\Attributes\Test;

use Livewire\Livewire;
use Modules\Tax\Models\TaxType;
use Modules\Tax\Filament\Admin\Resources\TaxResource\Pages\CreateTax;
use Modules\Tax\Filament\Admin\Resources\TaxResource\Pages\ListTaxes;
use Tests\TestCase;

class TaxResourceTest extends TestCase
{

    #[Test]

    public function it_renders_the_tax_filament_resource_form_correctly(): void {
        Livewire::test(CreateTax::class)
            ->assertFormFieldExists('name')
            ->assertFormFieldExists('type')
            ->assertFormFieldExists('rate')
            ->assertFormFieldExists('description');
    }

    #[Test]

    public function it_submits_the_tax_form(): void {
        $data = ['name' => 'TESTVAT', 'type' => 'percentage', 'rate' => 15, 'description' => 'Value-added tax'];
        Livewire::test(CreateTax::class)
            ->fillForm($data)
            ->call('create')
            ->assertHasNoActionErrors()
            ->assertNotified()
            ->assertHasNoErrors();

        $this->assertTrue(TaxType::where('name', 'TESTVAT')->exists());

        // delete the record
        TaxType::where('name', 'TESTVAT')->delete();
        $this->assertFalse(TaxType::where('name', 'TESTVAT')->exists());
    }

    #[Test]

    public function it_can_delete_a_tax(): void {
        // Create a tax entry in the database
        $tax = TaxType::create([
            'name' => 'Test Tax',
            'type' => 'percentage',
            'rate' => 5,
            'description' => 'Test tax description',
        ]);

        // Ensure the tax is created in the database
        $this->assertTrue(TaxType::where('name', 'Test Tax')->exists());

        // Test deletion via the ListTaxes page
        Livewire::test(ListTaxes::class)
            ->callTableAction('delete', $tax)  // Use the delete action on the tax record
            ->assertHasNoTableActionErrors();  // Ensure there are no errors

        // Ensure the tax entry is deleted from the database
        $this->assertFalse(TaxType::where('name', 'Test Tax')->exists());
    }
}
