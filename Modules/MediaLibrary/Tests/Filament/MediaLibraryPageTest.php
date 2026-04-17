<?php

namespace Modules\MediaLibrary\Tests\Filament;

use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;

class MediaLibraryPageTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
    }

    #[Test]
    public function it_media_library_admin_page_accessible(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/admin/media-library');
        $response->assertSuccessful();
    }
}
