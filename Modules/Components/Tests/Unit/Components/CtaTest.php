<?php

namespace Modules\Components\Tests\Unit\Components;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class CtaTest extends TestCase
{
    #[Test]
    public function it_renders_a_centered_stacked_block_by_default(): void
    {
        $output = Blade::render('<x-cta><a href="#">Go</a></x-cta>');

        $this->assertStringContainsString('cta-block', $output);
        $this->assertStringContainsString('text-center', $output);
        $this->assertStringContainsString('Go', $output);
    }

    #[Test]
    public function it_renders_the_heading_slot(): void
    {
        $output = Blade::render(
            '<x-cta><x-slot:heading><h3>Ready?</h3></x-slot:heading><a href="#">Go</a></x-cta>'
        );

        $this->assertStringContainsString('Ready?', $output);
    }

    #[Test]
    public function it_switches_to_inline_layout(): void
    {
        $output = Blade::render('<x-cta layout="inline" align="start"><a href="#">Go</a></x-cta>');

        $this->assertStringContainsString('d-md-flex', $output);
        $this->assertStringContainsString('text-start', $output);
    }
}
