<?php

namespace Modules\Components\Tests\Unit\Components;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class PriceListItemTest extends TestCase
{
    #[Test]
    public function it_renders_title_description_and_price(): void
    {
        $output = Blade::render(
            '<x-price-list-item title="Starters" description="Soup of the day" price="$5" />'
        );

        $this->assertStringContainsString('<h6', $output);
        $this->assertStringContainsString('Starters', $output);
        $this->assertStringContainsString('Soup of the day', $output);
        $this->assertStringContainsString('$5', $output);
        $this->assertStringContainsString('price-list-content', $output);
    }

    #[Test]
    public function it_renders_a_divider_by_default(): void
    {
        $output = Blade::render('<x-price-list-item description="Item" price="$1" />');

        $this->assertStringContainsString('price-list-hr', $output);
    }

    #[Test]
    public function it_omits_the_divider_when_disabled(): void
    {
        $output = Blade::render('<x-price-list-item description="Item" price="$1" :show-divider="false" />');

        $this->assertStringNotContainsString('price-list-hr', $output);
    }
}
