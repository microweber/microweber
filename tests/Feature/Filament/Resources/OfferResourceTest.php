<?php

namespace Tests\Feature\Filament\Resources;

use Livewire\Livewire;
use Modules\Offer\Filament\Admin\Resources\OfferResource;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\FilamentResourceTestCase;

class OfferResourceTest extends FilamentResourceTestCase
{
    protected function getResourceClass(): string
    {
        return OfferResource::class;
    }

    #[Test]
    public function it_resource_has_correct_model(): void
    {
        $this->assertNotNull(OfferResource::getModel());
    }

    #[Test]
    public function it_resource_class_exists(): void
    {
        $this->assertTrue(class_exists(OfferResource::class));
    }
}
