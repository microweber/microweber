<?php

namespace Modules\Components\Tests\Unit\Components;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class TabTest extends TestCase
{
    #[Test]
    public function it_renders_nav_tabs_and_a_tab_content_region(): void
    {
        $output = Blade::render(
            '<x-tab id="t1"><x-slot:navItems><li>Home</li></x-slot:navItems><x-tab-item id="p1" :active="true">content</x-tab-item></x-tab>'
        );

        $this->assertStringContainsString('nav nav-tabs', $output);
        $this->assertStringContainsString('id="t1"', $output);
        $this->assertStringContainsString('tab-content', $output);
        $this->assertStringContainsString('content', $output);
    }

    #[Test]
    public function it_renders_pills_when_requested(): void
    {
        $output = Blade::render(
            '<x-tab id="t1" :pills="true"><x-slot:navItems><li>x</li></x-slot:navItems></x-tab>'
        );

        $this->assertStringContainsString('nav-pills', $output);
    }

    #[Test]
    public function it_renders_an_active_tab_item_as_a_tabpanel(): void
    {
        $output = Blade::render('<x-tab-item id="p1" :active="true">c</x-tab-item>');

        $this->assertStringContainsString('tab-pane fade', $output);
        $this->assertStringContainsString('show active', $output);
        $this->assertStringContainsString('role="tabpanel"', $output);
        $this->assertStringContainsString('id="p1"', $output);
    }
}
