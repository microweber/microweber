<?php

namespace Modules\Components\Tests\Unit\Components;

use PHPUnit\Framework\Attributes\Test;

use Tests\TestCase;

class SectionTest extends TestCase
{

    #[Test]

    public function it_renders_the_section_component(): void {
        $view = $this->blade('<x-section title="Test Title" class="custom-class">Content goes here</x-section>');
        $view->assertSee('<section class="custom-class', false);
        $view->assertSee('Content goes here', false);
        $view->assertSee('title="Test Title"', false);
    }
}
