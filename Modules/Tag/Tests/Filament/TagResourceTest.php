<?php

namespace Modules\Tag\Tests\Filament;

use Livewire\Livewire;
use Modules\Tag\Filament\Resources\TagResource;
use Modules\Tag\Filament\Resources\TagResource\Pages\ListTags;
use Modules\Tag\Filament\Resources\TagGroupResource;
use Modules\Tag\Filament\Resources\TagGroupResource\Pages\ListTagGroups;
use Tests\Feature\Filament\FilamentResourceTestCase;
use PHPUnit\Framework\Attributes\Test;

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

        Livewire::test(ListTags::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_can_render_tag_groups_list_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(ListTagGroups::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_non_admin_cannot_access_tags(): void
    {
        $this->actingAsUser();

        $response = $this->get(TagResource::getUrl('index'));
        $response->assertForbidden();
    }
}
