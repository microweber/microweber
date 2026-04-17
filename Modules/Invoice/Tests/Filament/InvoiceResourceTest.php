<?php

namespace Modules\Invoice\Tests\Filament;

use Livewire\Livewire;
use Modules\Invoice\Filament\Resources\InvoiceResource;
use Modules\Invoice\Filament\Resources\InvoiceResource\Pages\ListInvoices;
use Modules\Invoice\Filament\Pages\AdminShopInvoicesPage;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\FilamentResourceTestCase;

class InvoiceResourceTest extends FilamentResourceTestCase
{
    protected function getResourceClass(): string
    {
        return InvoiceResource::class;
    }

    #[Test]
    public function it_can_render_list_page(): void
    {
        $this->actingAsAdmin();
        Livewire::test(ListInvoices::class)->assertSuccessful();
    }

    #[Test]
    public function it_can_render_invoices_admin_page(): void
    {
        $this->actingAsAdmin();
        Livewire::test(AdminShopInvoicesPage::class)->assertSuccessful();
    }

    #[Test]
    public function it_resource_has_correct_model(): void
    {
        $this->assertNotNull(InvoiceResource::getModel());
    }
}
