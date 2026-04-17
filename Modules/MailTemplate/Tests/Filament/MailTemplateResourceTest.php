<?php

namespace Modules\MailTemplate\Tests\Filament;

use Livewire\Livewire;
use Modules\MailTemplate\Filament\Resources\MailTemplateResource;
use Modules\MailTemplate\Filament\Resources\MailTemplateResource\Pages\ListMailTemplates;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\FilamentResourceTestCase;

class MailTemplateResourceTest extends FilamentResourceTestCase
{
    protected function getResourceClass(): string
    {
        return MailTemplateResource::class;
    }

    #[Test]
    public function it_can_render_list_page(): void
    {
        $this->actingAsAdmin();
        Livewire::test(ListMailTemplates::class)->assertSuccessful();
    }

    #[Test]
    public function it_resource_has_correct_model(): void
    {
        $this->assertNotNull(MailTemplateResource::getModel());
    }
}
