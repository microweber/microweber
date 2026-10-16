<?php

namespace Modules\DigitalProduct\Tests\Unit\Filament;

use Livewire\Livewire;
use Modules\Product\Filament\Admin\Resources\ProductResource\Pages\CreateProduct;
use Modules\Product\Filament\Admin\Resources\ProductResource\Pages\EditProduct;
use Modules\Product\Models\Product;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;

class DownloadableProductToggleTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected ?Product $product = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
    }

    #[Test]
    public function create_form_exposes_the_downloadable_toggle(): void
    {
        Livewire::test(CreateProduct::class)
            ->assertSuccessful()
            ->assertFormFieldExists('is_downloadable');
    }

    #[Test]
    public function edit_form_reflects_a_downloadable_products_subtype(): void
    {
        $this->product = new Product(['subtype' => 'downloadable_product']);
        $this->product->title = 'dp-toggle-' . uniqid();
        $this->product->url = $this->product->title;
        $this->product->save();

        Livewire::test(EditProduct::class, ['record' => $this->product->id])
            ->assertSuccessful()
            ->assertFormSet(['is_downloadable' => true]);
    }

    protected function tearDown(): void
    {
        if ($this->product) {
            $this->product->forceDelete();
        }

        parent::tearDown();
    }
}
