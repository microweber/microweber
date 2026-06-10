<?php

namespace Modules\Components\Tests\Unit\Components;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class TeamCardTest extends TestCase
{
    #[Test]
    public function it_renders_name_role_and_bio(): void
    {
        $output = Blade::render(
            '<x-team-card name="John Smith" role="Developer" bio="Builds things." />'
        );

        $this->assertStringContainsString('class="card', $output);
        $this->assertStringContainsString('John Smith', $output);
        $this->assertStringContainsString('Developer', $output);
        $this->assertStringContainsString('Builds things.', $output);
    }

    #[Test]
    public function it_renders_a_circular_photo_when_image_is_set(): void
    {
        $output = Blade::render('<x-team-card name="John" image="/photo.jpg" />');

        $this->assertStringContainsString('rounded-circle', $output);
        $this->assertStringContainsString('/photo.jpg', $output);
    }

    #[Test]
    public function it_renders_a_website_link(): void
    {
        $output = Blade::render('<x-team-card name="John" website="https://example.com" />');

        $this->assertStringContainsString('href="https://example.com"', $output);
        $this->assertStringContainsString('rel="noopener noreferrer"', $output);
    }
}
