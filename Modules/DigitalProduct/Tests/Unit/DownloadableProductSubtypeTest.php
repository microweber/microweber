<?php

namespace Modules\DigitalProduct\Tests\Unit;

use Modules\Product\Models\Product;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * The Product model lets a product be a downloadable product (subtype
 * 'downloadable_product') while defaulting to a regular 'product' when none is
 * given, and always staying content_type 'product'.
 */
class DownloadableProductSubtypeTest extends TestCase
{
    protected array $ids = [];

    #[Test]
    public function new_product_defaults_to_product_subtype(): void
    {
        $p = new Product();
        $this->assertSame('product', $p->subtype);
        $this->assertSame('product', $p->content_type);
    }

    #[Test]
    public function a_product_can_be_a_downloadable_product_and_it_persists(): void
    {
        $p = new Product(['subtype' => 'downloadable_product']);
        $p->title = 'dp-subtype-' . uniqid();
        $p->url = $p->title;
        $p->save();
        $this->ids[] = $p->id;

        $this->assertSame('downloadable_product', $p->subtype);
        $this->assertSame('product', $p->content_type);

        $fresh = Product::find($p->id);
        $this->assertSame('downloadable_product', $fresh->subtype);
        $this->assertSame('product', $fresh->content_type);
    }

    protected function tearDown(): void
    {
        if (!empty($this->ids)) {
            Product::whereIn('id', $this->ids)->forceDelete();
        }

        parent::tearDown();
    }
}
