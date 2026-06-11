<?php

namespace Modules\Components\Tests\Unit\Components;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class IconFeatureTest extends TestCase
{
    #[Test]
    public function it_renders_icon_title_and_text(): void
    {
        $output = Blade::render('<x-icon-feature icon="mdi-star" title="Fast" text="It is quick." />');

        $this->assertStringContainsString('mdi-star', $output);
        $this->assertStringContainsString('<h4', $output);
        $this->assertStringContainsString('Fast', $output);
        $this->assertStringContainsString('It is quick.', $output);
    }

    #[Test]
    public function it_uses_horizontal_layout_by_default_and_honours_block(): void
    {
        $this->assertStringContainsString('d-flex', Blade::render('<x-icon-feature title="X" />'));
        $this->assertStringContainsString('d-block', Blade::render('<x-icon-feature title="X" layout="block" />'));
    }

    #[Test]
    public function it_applies_the_icon_size(): void
    {
        $output = Blade::render('<x-icon-feature icon="mdi-star" icon-size="64px" />');

        $this->assertStringContainsString('font-size: 64px', $output);
    }
}
