<?php

namespace Modules\Components\Tests\Unit\Components;

use Tests\TestCase;

class SectionTest extends TestCase
{

    public function testRendersTheSectionComponent()
    {
        $view = $this->blade('<x-section title="Test Title" class="custom-class">Content goes here</x-section>');
        $view->assertSee('<section class="custom-class', false);
        $view->assertSee('Content goes here', false);
        $view->assertSee('title="Test Title"', false);
    }
}
