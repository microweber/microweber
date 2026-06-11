<?php

namespace Modules\Components\Tests\Unit\Components;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class FeatureItemTest extends TestCase
{
    #[Test]
    public function it_renders_icon_title_and_text(): void
    {
        $output = Blade::render(
            '<x-feature-item icon="mdi-star" title="Fast" text="It is quick." />'
        );

        $this->assertStringContainsString('mdi-star', $output);
        $this->assertStringContainsString('<h4', $output);
        $this->assertStringContainsString('Fast', $output);
        $this->assertStringContainsString('It is quick.', $output);
    }

    #[Test]
    public function it_uses_the_default_column_classes(): void
    {
        $output = Blade::render('<x-feature-item title="X" />');

        $this->assertStringContainsString('col-md-6', $output);
        $this->assertStringContainsString('col-lg-4', $output);
    }

    #[Test]
    public function it_honours_a_custom_col_class(): void
    {
        $output = Blade::render('<x-feature-item title="X" col-class="col-6" />');

        $this->assertStringContainsString('col-6', $output);
    }
}
