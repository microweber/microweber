<?php

namespace Modules\Components\Tests\Unit\Components;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class ContentCardTest extends TestCase
{
    #[Test]
    public function it_renders_title_description_and_date(): void
    {
        $output = Blade::render(
            '<x-content-card title="Getting Started" description="Learn the basics." date="2026" />'
        );

        $this->assertStringContainsString('class="card', $output);
        $this->assertStringContainsString('card-title', $output);
        $this->assertStringContainsString('Getting Started', $output);
        $this->assertStringContainsString('Learn the basics.', $output);
        $this->assertStringContainsString('2026', $output);
    }

    #[Test]
    public function it_wraps_image_and_title_in_a_link_when_link_is_set(): void
    {
        $output = Blade::render(
            '<x-content-card title="Post" image="/cover.jpg" link="/post" />'
        );

        $this->assertStringContainsString('card-img-top', $output);
        $this->assertStringContainsString('href="/post"', $output);
        $this->assertStringContainsString('/cover.jpg', $output);
    }
}
