<?php

namespace Modules\Components\Tests\Unit\Components;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class TestimonialCardTest extends TestCase
{
    #[Test]
    public function it_renders_name_role_company_and_quote(): void
    {
        $output = Blade::render(
            '<x-testimonial-card name="Jane Doe" role="CEO" company="Acme" content="Great product!" />'
        );

        $this->assertStringContainsString('class="card', $output);
        $this->assertStringContainsString('Jane Doe', $output);
        $this->assertStringContainsString('CEO', $output);
        $this->assertStringContainsString('Acme', $output);
        $this->assertStringContainsString('Great product!', $output);
    }

    #[Test]
    public function it_renders_a_circular_avatar_when_image_is_set(): void
    {
        $output = Blade::render('<x-testimonial-card name="Jane" image="/avatar.jpg" />');

        $this->assertStringContainsString('rounded-circle', $output);
        $this->assertStringContainsString('/avatar.jpg', $output);
    }

    #[Test]
    public function it_merges_custom_classes(): void
    {
        $output = Blade::render('<x-testimonial-card name="Jane" class="shadow" />');

        $this->assertStringContainsString('shadow', $output);
    }
}
