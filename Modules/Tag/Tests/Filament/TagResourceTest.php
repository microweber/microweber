<?php

namespace Modules\Tag\Tests\Filament;

use Livewire\Livewire;
use Modules\Tag\Filament\Resources\TagResource;
use Modules\Tag\Filament\Resources\TagResource\Pages\ListTags;
use Modules\Tag\Filament\Resources\TagGroupResource;
use Modules\Tag\Filament\Resources\TagGroupResource\Pages\ListTagGroups;
use Modules\Tag\Filament\Resources\TaggedResource;
use Modules\Tag\Filament\Resources\TaggedResource\Pages\ListTagged;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\FilamentResourceTestCase;

class TagResourceTest extends FilamentResourceTestCase
{
    protected function getResourceClass(): string
    {
        return TagResource::class;
    }

    #[Test]
    public function it_can_render_tags_list_page(): void
    {
        $this->actingAsAdmin();
        Livewire::test(ListTags::class)->assertSuccessful();
    }

    #[Test]
    public function it_can_render_tag_groups_list_page(): void
    {
        $this->actingAsAdmin();
        Livewire::test(ListTagGroups::class)->assertSuccessful();
    }

    #[Test]
    public function it_can_render_tagged_list_page(): void
    {
        $this->actingAsAdmin();
        Livewire::test(ListTagged::class)->assertSuccessful();
    }

    #[Test]
    public function it_resource_has_correct_model(): void
    {
        $this->assertNotNull(TagResource::getModel());
    }
}
