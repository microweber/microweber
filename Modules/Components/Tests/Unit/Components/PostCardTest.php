<?php

namespace Modules\Components\Tests\Unit\Components;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class PostCardTest extends TestCase
{
    #[Test]
    public function it_renders_title_description_and_date(): void
    {
        $output = Blade::render(
            '<x-post-card title="Story Title" description="The body." date="2023-10-18" />'
        );

        $this->assertStringContainsString('class="card', $output);
        $this->assertStringContainsString('card-title', $output);
        $this->assertStringContainsString('Story Title', $output);
        $this->assertStringContainsString('The body.', $output);
        $this->assertStringContainsString('2023-10-18', $output);
    }

    #[Test]
    public function it_renders_a_read_more_button_when_link_is_set(): void
    {
        $output = Blade::render('<x-post-card title="Story" link="/my-post" read-more-text="Read More" />');

        $this->assertStringContainsString('href="/my-post"', $output);
        $this->assertStringContainsString('Read More', $output);
        $this->assertStringContainsString('btn-outline-primary', $output);
    }

    #[Test]
    public function it_omits_the_read_more_button_without_a_link(): void
    {
        $output = Blade::render('<x-post-card title="Story" read-more-text="Read More" />');

        $this->assertStringNotContainsString('Read More', $output);
    }
}
