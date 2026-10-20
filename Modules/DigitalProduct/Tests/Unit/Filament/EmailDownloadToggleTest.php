<?php

namespace Modules\DigitalProduct\Tests\Unit\Filament;

use Livewire\Livewire;
use Modules\DigitalProduct\Services\DigitalProductService;
use Modules\Product\Filament\Admin\Resources\ProductResource\Pages\CreateProduct;
use Modules\Product\Filament\Admin\Resources\ProductResource\Pages\EditProduct;
use Modules\Product\Models\Product;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;

/**
 * The per-product "Email download link on order" toggle is present on the
 * Product form and round-trips through the content_data flag the delivery
 * service reads (DigitalProductService::EMAIL_FLAG).
 */
class EmailDownloadToggleTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected ?Product $product = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
    }

    #[Test]
    public function create_form_exposes_the_email_download_toggle(): void
    {
        Livewire::test(CreateProduct::class)
            ->assertSuccessful()
            ->assertFormFieldExists('email_download_on_order');
    }

    #[Test]
    public function edit_form_reflects_a_products_email_download_flag(): void
    {
        $this->product = new Product(['subtype' => 'downloadable_product']);
        $this->product->title = 'dp-email-toggle-' . uniqid();
        $this->product->url = $this->product->title;
        $this->product->save();
        $this->product->setContentData([DigitalProductService::EMAIL_FLAG => '1']);
        $this->product->save();

        Livewire::test(EditProduct::class, ['record' => $this->product->id])
            ->assertSuccessful()
            ->assertFormSet([
                'is_downloadable' => true,
                'email_download_on_order' => true,
            ]);
    }

    protected function tearDown(): void
    {
        if ($this->product) {
            $this->product->forceDelete();
        }

        parent::tearDown();
    }
}
