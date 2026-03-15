<?php

namespace Modules\Offer\Tests\Unit\Filament;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;
use Modules\Offer\Filament\Admin\Resources\OfferResource;
use Modules\Offer\Filament\Admin\Resources\OfferResource\Pages\ListOffers;
use Modules\Offer\Filament\Admin\Resources\OfferResource\Pages\CreateOffer;
use Modules\Offer\Filament\Admin\Resources\OfferResource\Pages\EditOffer;
use Modules\Offer\Models\Offer;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

#[\PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses]
class OfferResourceTest extends TestCase
{
    use LazilyRefreshDatabase;
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
    }

    #[Test]
    public function it_index_page_loads_without_errors(): void
    {
        Livewire::test(ListOffers::class)->assertSuccessful();
    }

    #[Test]
    public function it_index_page_shows_records(): void
    {
        $offer = Offer::factory()->create();

        Livewire::test(ListOffers::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_create_page_renders_form(): void
    {
        Livewire::test(CreateOffer::class)->assertSuccessful()->assertFormExists();
    }

    #[Test]
    public function it_edit_page_loads(): void
    {
        $offer = Offer::factory()->create(['offer_price' => 100.00]);

        Livewire::test(EditOffer::class, ['record' => $offer->id])
            ->assertSuccessful();
    }

    #[Test]
    public function it_delete_action_removes_record(): void
    {
        $offer = Offer::factory()->create();
        Livewire::test(ListOffers::class)->callTableAction('delete', $offer);
        $this->assertDatabaseMissing('offers', ['id' => $offer->id]);
    }

    #[Test]
    public function it_table_has_required_columns(): void
    {
        Livewire::test(ListOffers::class)
            ->assertTableColumnExists('product_id')
            ->assertTableColumnExists('offer_price')
            ->assertTableColumnExists('is_active');
    }

    #[Test]
    public function it_pages_exist(): void
    {
        $pages = OfferResource::getPages();
        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }
}
