<?php

namespace Modules\Components\Tests\Unit\Components;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class AccordionTest extends TestCase
{
    #[Test]
    public function it_renders_an_accordion_with_an_explicit_id(): void
    {
        $output = Blade::render('<x-accordion id="demo">items</x-accordion>');

        $this->assertStringContainsString('class="accordion"', $output);
        $this->assertStringContainsString('id="demo"', $output);
    }

    #[Test]
    public function it_renders_the_flush_modifier(): void
    {
        $output = Blade::render('<x-accordion id="demo" :flush="true">x</x-accordion>');

        $this->assertStringContainsString('accordion-flush', $output);
    }

    #[Test]
    public function it_renders_an_open_item_with_bootstrap_collapse_wiring(): void
    {
        $output = Blade::render(
            '<x-accordion id="demo"><x-accordion-item id="q1" title="Question?" :open="true" parent="demo">Answer.</x-accordion-item></x-accordion>'
        );

        $this->assertStringContainsString('accordion-item', $output);
        $this->assertStringContainsString('data-bs-toggle="collapse"', $output);
        $this->assertStringContainsString('data-bs-target="#collapse-q1"', $output);
        $this->assertStringContainsString('data-bs-parent="#demo"', $output);
        $this->assertStringContainsString('Question?', $output);
        $this->assertStringContainsString('Answer.', $output);
        $this->assertStringContainsString('aria-expanded="true"', $output);
        $this->assertStringContainsString('collapse show', $output);
    }

    #[Test]
    public function it_renders_a_closed_item_collapsed(): void
    {
        $output = Blade::render(
            '<x-accordion id="demo"><x-accordion-item id="q2" title="Q">A</x-accordion-item></x-accordion>'
        );

        $this->assertStringContainsString('accordion-button collapsed', $output);
        $this->assertStringContainsString('aria-expanded="false"', $output);
    }
}
