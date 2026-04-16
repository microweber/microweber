<?php

namespace Modules\MediaLibrary\Tests\Filament;

use Livewire\Livewire;
use Modules\MediaLibrary\Filament\Admin\Pages\MediaLibrary;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class MediaLibraryPageTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
    }

    #[Test]
    public function it_can_render_media_library_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(MediaLibrary::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_non_admin_cannot_access_media_library(): void
    {
        $this->actingAsUser();

        $response = $this->get(MediaLibrary::getUrl());
        $response->assertForbidden();
    }
}
