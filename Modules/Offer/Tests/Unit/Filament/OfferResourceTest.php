<?php

namespace Modules\Offer\Tests\Unit\Filament;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Offer\Filament\Admin\Resources\OfferResource;
use Modules\Offer\Filament\Admin\Resources\OfferResource\Pages\ListOffers;
use Modules\Offer\Filament\Admin\Resources\OfferResource\Pages\CreateOffer;
use Modules\Offer\Filament\Admin\Resources\OfferResource\Pages\EditOffer;
use Modules\Offer\Models\Offer;
use Modules\Product\Models\Product;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class OfferResourceTest extends TestCase
{
    use RefreshDatabase;
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
    public function it_index_page_shows_all_records(): void
    {
        $offers = Offer::factory()->count(3)->create();
        Livewire::test(ListOffers::class)->assertCanSeeTableRecords($offers);
    }

    #[Test]
    public function it_index_page_supports_pagination(): void
    {
        Offer::factory()->count(15)->create();
        Livewire::test(ListOffers::class)->assertSuccessful();
    }

    #[Test]
    public function it_create_page_renders_form(): void
    {
        Livewire::test(CreateOffer::class)->assertSuccessful()->assertFormExists();
    }

    #[Test]
    public function it_create_page_saves_new_record(): void
    {
        $product = Product::factory()->create();

        Livewire::test(CreateOffer::class)
            ->fillForm([
                'product_id' => $product->id,
                'price_id' => 1,
                'offer_price' => 50.00,
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('offers', ['product_id' => $product->id, 'offer_price' => 50.00]);
    }

    #[Test]
    public function it_edit_page_updates_record(): void
    {
        $offer = Offer::factory()->create(['offer_price' => 100.00]);

        Livewire::test(EditOffer::class, ['record' => $offer->id])
            ->fillForm(['offer_price' => 75.00])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('offers', ['id' => $offer->id, 'offer_price' => 75.00]);
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
    public function it_can_filter_by_active(): void
    {
        $active = Offer::factory()->create(['is_active' => true]);
        $inactive = Offer::factory()->create(['is_active' => false]);

        Livewire::test(ListOffers::class)
            ->filterTable('is_active', true)
            ->assertCanSeeTableRecords([$active])
            ->assertCanNotSeeTableRecords([$inactive]);
    }
}
