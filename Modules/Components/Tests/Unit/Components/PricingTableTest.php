<?php

namespace Modules\Components\Tests\Unit\Components;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class PricingTableTest extends TestCase
{
    #[Test]
    public function it_renders_a_row_cols_grid_wrapper(): void
    {
        $output = Blade::render('<x-pricing-table>content</x-pricing-table>');

        $this->assertStringContainsString('row row-cols-1', $output);
        $this->assertStringContainsString('row-cols-md-3', $output); // default columns
        $this->assertStringContainsString('content', $output);
    }

    #[Test]
    public function it_honours_the_columns_prop(): void
    {
        $output = Blade::render('<x-pricing-table :columns="2">x</x-pricing-table>');

        $this->assertStringContainsString('row-cols-md-2', $output);
    }

    #[Test]
    public function it_merges_custom_classes(): void
    {
        $output = Blade::render('<x-pricing-table class="my-grid">x</x-pricing-table>');

        $this->assertStringContainsString('my-grid', $output);
    }
}
