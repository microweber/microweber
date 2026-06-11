<?php

namespace Modules\Components\Tests\Unit\Components;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class ImageCardTest extends TestCase
{
    #[Test]
    public function it_renders_an_image_with_src_and_alt(): void
    {
        $output = Blade::render('<x-image-card src="/photo.jpg" alt="A photo" />');

        $this->assertStringContainsString('src="/photo.jpg"', $output);
        $this->assertStringContainsString('alt="A photo"', $output);
    }

    #[Test]
    public function it_uses_the_default_wrapper_and_lazy_loads_by_default(): void
    {
        $output = Blade::render('<x-image-card src="/x.jpg" />');

        $this->assertStringContainsString('img-as-background square', $output);
        $this->assertStringContainsString('loading="lazy"', $output);
    }

    #[Test]
    public function it_can_disable_lazy_loading_and_set_classes(): void
    {
        $output = Blade::render('<x-image-card src="/x.jpg" :lazy="false" wrapper-class="my-wrap" img-class="rounded" />');

        $this->assertStringNotContainsString('loading="lazy"', $output);
        $this->assertStringContainsString('my-wrap', $output);
        $this->assertStringContainsString('rounded', $output);
    }
}
