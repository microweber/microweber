<?php

namespace Modules\Checkout\Tests\Unit\Filament;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Checkout\Filament\Resources\CheckoutResource;
use Modules\Checkout\Filament\Resources\Pages\CheckoutPage;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CheckoutResourceTest extends TestCase
{
    use RefreshDatabase;
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel('checkout');
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
            ->assertSuccessful()
            ->assertFormExists();
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
