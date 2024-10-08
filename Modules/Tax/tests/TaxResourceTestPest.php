<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Tax\Models\TaxType;
use Modules\Tax\Filament\Admin\Resources\TaxResource\Pages\CreateTax;
use Modules\Tax\Filament\Admin\Resources\TaxResource\Pages\ListTaxes;

uses(\Tests\TestCase::class);
//uses(RefreshDatabase::class);

it('renders the tax filament resource form correctly', function () {
    Livewire::test(CreateTax::class)
        ->assertFormFieldExists('name')
        ->assertFormFieldExists('type')
        ->assertFormFieldExists('rate')
        ->assertFormFieldExists('description');
});

it('submits the tax form', function () {

    $data = ['name' => 'TESTVAT', 'type' => 'percentage', 'rate' => 15, 'description' => 'Value-added tax'];
    Livewire::test(CreateTax::class)
        ->fillForm($data)
        ->call('create')
        ->assertHasNoActionErrors()
        ->assertNotified()
        ->assertHasNoErrors();

    expect(TaxType::where('name', 'TESTVAT')->exists())->toBeTrue();
});

it('can delete a tax', function () {
    // Create a tax entry in the database
    $tax = TaxType::create([
        'name' => 'Test Tax',
        'type' => 'percentage',
        'rate' => 5,
        'description' => 'Test tax description',
    ]);

    // Ensure the tax is created in the database
    expect(TaxType::where('name', 'Test Tax')->exists())->toBeTrue();

    // Test deletion via the ListTaxes page
    Livewire::test(ListTaxes::class)
        ->callTableAction('delete', $tax)  // Use the delete action on the tax record
        ->assertHasNoTableActionErrors();  // Ensure there are no errors

    // Ensure the tax entry is deleted from the database
    expect(TaxType::where('name', 'Test Tax')->exists())->toBeFalse();
});
