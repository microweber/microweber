<?php

namespace Modules\Media\Tests\Filament;

use Modules\Media\Filament\Resources\MediaResource;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;

class MediaResourceTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
    }

    #[Test]
    public function it_resource_exists(): void
    {
        $this->assertTrue(class_exists(MediaResource::class));
    }

    #[Test]
    public function it_resource_has_correct_model(): void
    {
        $this->assertNotNull(MediaResource::getModel());
    }
}
