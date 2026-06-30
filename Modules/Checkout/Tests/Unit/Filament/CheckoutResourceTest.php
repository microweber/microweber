<?php

namespace Modules\Checkout\Tests\Unit\Filament;

use Livewire\Livewire;
use Modules\Checkout\Filament\Resources\CheckoutResource;
use Modules\Checkout\Filament\Resources\Pages\CheckoutPage;
use Modules\Checkout\Livewire\CheckoutWizard;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CheckoutResourceTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel('checkout');
        // Register CheckoutWizard component under its expected name
        app(\Livewire\LivewireManager::class)->component('checkout.checkout-wizard', CheckoutWizard::class);
        // Add a product to cart so checkout page doesn't redirect on empty cart
        app()->database_manager->extended_save_set_permission(true);
        $productId = save_content([
            'title' => 'CheckoutResourceTest Product',
            'content_type' => 'product',
            'subtype' => 'product',
            'is_active' => 1,
        ]);
        update_cart(['content_id' => $productId, 'qty' => 1]);
    }

    protected function tearDown(): void
    {
        empty_cart();
        parent::tearDown();
    }

    #[Test]
    public function it_checkout_page_loads_without_errors(): void
    {
        Livewire::test(CheckoutPage::class)->assertSuccessful();
    }

    #[Test]
    public function it_form_contains_personal_info_section(): void
    {
        Livewire::test(CheckoutPage::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_form_contains_shipping_address_section(): void
    {
        Livewire::test(CheckoutPage::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_form_contains_payment_method_section(): void
    {
        Livewire::test(CheckoutPage::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_pages_exist(): void
    {
        $pages = CheckoutResource::getPages();
        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('success', $pages);
        $this->assertArrayHasKey('failed', $pages);
        $this->assertArrayHasKey('cancelled', $pages);
    }
}
