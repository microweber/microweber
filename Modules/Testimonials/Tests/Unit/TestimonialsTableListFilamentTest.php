<?php

namespace Modules\Testimonials\Tests\Unit;

use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Testimonials\Filament\TestimonialsTableList;
use Modules\Testimonials\Models\Testimonial;
use Tests\TestCase;

class TestimonialsTableListFilamentTest extends TestCase
{
    public function testTestimonialsTableListForm()
    {
        $moduleId = 'testimonials-module-id-test-' . uniqid();
        $moduleType = 'testimonials';

        $name = 'Test Testimonial Name';
        $record = new Testimonial();
        $record->rel_id = $moduleId;
        $record->rel_type = $moduleType;
        $record->name = $name;
        $record->content = 'This is the content of the testimonial.';
        $record->client_company = 'Test Company';
        $record->client_role = 'Test Role';
        $record->client_website = 'https://example.com';
        $record->save();

        Livewire::test(TestimonialsTableList::class, ['rel_id' => $moduleId, 'rel_type' => $moduleType])
            ->assertSee($name)
            ->callTableAction('edit', $record)
            ->assertSee($name)
            ->callMountedTableAction()
            ->assertHasNoTableActionErrors();

        // cleanup
        $record->delete();
    }
}
