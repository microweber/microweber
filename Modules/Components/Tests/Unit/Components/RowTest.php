<?php

namespace Modules\Components\Tests\Unit\Components;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class RowTest extends TestCase
{

    #[Test]

    public function it_renders_a_row(): void {
        $bladeString ='<x-row></x-row>';

        $output = Blade::render($bladeString);

        $this->assertStringContainsString('class="row', $output);
    }

    #[Test]

    public function it_renders_a_flex_row(): void {
        $bladeString = '<x-row flex></x-row>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('d-flex', $output);
    }


    #[Test]


    public function it_renders_a_flex_wrap_row(): void {
        $bladeString = '<x-row flex-wrap></x-row>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('flex-wrap', $output);
    }


    #[Test]


    public function it_renders_a_flex_no_wrap_row(): void {
        $bladeString = '<x-row flex-no-wrap></x-row>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('flex-nowrap', $output);
    }
}
