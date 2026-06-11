<?php

namespace Modules\Components\Tests\Unit\Components;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class StatCounterTest extends TestCase
{
    #[Test]
    public function it_renders_value_and_label(): void
    {
        $output = Blade::render('<x-stat-counter value="1,200" label="Customers" />');

        $this->assertStringContainsString('stat-counter', $output);
        $this->assertStringContainsString('1,200', $output);
        $this->assertStringContainsString('Customers', $output);
    }

    #[Test]
    public function it_renders_prefix_and_suffix(): void
    {
        $output = Blade::render('<x-stat-counter value="99.9" prefix="~" suffix="%" />');

        $this->assertStringContainsString('stat-prefix', $output);
        $this->assertStringContainsString('~', $output);
        $this->assertStringContainsString('stat-suffix', $output);
        $this->assertStringContainsString('%', $output);
    }

    #[Test]
    public function it_omits_the_label_paragraph_when_empty(): void
    {
        $output = Blade::render('<x-stat-counter value="50" />');

        $this->assertStringNotContainsString('stat-counter-label', $output);
    }
}
