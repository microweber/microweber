<?php

namespace Modules\Components\Tests\Unit\Components;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class ProductCardTest extends TestCase
{
    #[Test]
    public function it_renders_title_and_price(): void
    {
        $output = Blade::render('<x-product-card title="Widget" price="$19.99" />');

        $this->assertStringContainsString('class="card', $output);
        $this->assertStringContainsString('Widget', $output);
        $this->assertStringContainsString('$19.99', $output);
    }

    #[Test]
    public function it_renders_add_to_cart_only_when_in_stock_with_price_and_content_id(): void
    {
        $output = Blade::render(
            '<x-product-card title="Widget" price="$19.99" :content-id="42" add-to-cart-text="Add to cart" />'
        );

        $this->assertStringContainsString('data-mw-cart-add-and-checkout="42"', $output);
        $this->assertStringContainsString('Add to cart', $output);
    }

    #[Test]
    public function it_omits_add_to_cart_without_a_content_id(): void
    {
        $output = Blade::render('<x-product-card title="Widget" price="$19.99" />');

        $this->assertStringNotContainsString('data-mw-cart-add-and-checkout', $output);
    }

    #[Test]
    public function it_dims_out_of_stock_products(): void
    {
        $output = Blade::render('<x-product-card title="Widget" price="$19.99" :in-stock="false" />');

        $this->assertStringContainsString('opacity-75', $output);
    }

    #[Test]
    public function it_shows_a_struck_through_original_price(): void
    {
        $output = Blade::render('<x-product-card title="Widget" price="$15" original-price="$20" />');

        $this->assertStringContainsString('text-decoration-line-through', $output);
        $this->assertStringContainsString('$20', $output);
    }
}
