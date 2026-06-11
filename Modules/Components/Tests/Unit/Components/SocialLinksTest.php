<?php

namespace Modules\Components\Tests\Unit\Components;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class SocialLinksTest extends TestCase
{
    #[Test]
    public function it_renders_the_wrapper_with_size_and_style_modifiers(): void
    {
        $output = Blade::render('<x-social-links />');

        $this->assertStringContainsString('social-links', $output);
        $this->assertStringContainsString('social-links-md', $output);
        $this->assertStringContainsString('social-links-default', $output);
    }

    #[Test]
    public function it_honours_size_and_style_props(): void
    {
        $output = Blade::render('<x-social-links size="lg" style="rounded" />');

        $this->assertStringContainsString('social-links-lg', $output);
        $this->assertStringContainsString('social-links-rounded', $output);
    }

    #[Test]
    public function it_renders_a_custom_slot_instead_of_the_module(): void
    {
        $output = Blade::render('<x-social-links><a class="my-link">FB</a></x-social-links>');

        $this->assertStringContainsString('my-link', $output);
    }
}
