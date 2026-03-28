<?php

namespace Modules\Coupons\Tests\Unit\Filament;

use Livewire\Livewire;
use Modules\Coupons\Filament\Resources\CouponResource;
use Modules\Coupons\Filament\Resources\CouponResource\Pages\ListCoupons;
use Modules\Coupons\Filament\Resources\CouponResource\Pages\CreateCoupon;
use Modules\Coupons\Filament\Resources\CouponResource\Pages\EditCoupon;
use Modules\Coupons\Models\Coupon;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CouponResourceTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
    }

    #[Test]
    public function it_index_page_loads_without_errors(): void
    {
        Livewire::test(ListCoupons::class)->assertSuccessful();
    }

    #[Test]
    public function it_index_page_shows_all_records(): void
    {
        $coupons = Coupon::factory()->count(3)->create();
        Livewire::test(ListCoupons::class)->assertCanSeeTableRecords($coupons);
    }

    #[Test]
    public function it_create_page_saves_new_record(): void
    {
        $uniqueCode = 'TEST' . uniqid();
        Livewire::test(CreateCoupon::class)
            ->fillForm([
                'coupon_name' => 'Test Coupon',
                'coupon_code' => $uniqueCode,
                'discount_type' => 'percentage',
                'discount_value' => 10,
                'is_active' => true,
                'conditional_rules' => [], // Empty array for repeater
            ])
            ->call('create')
            ->assertHasNoFormErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('cart_coupons', ['coupon_name' => 'Test Coupon', 'coupon_code' => $uniqueCode]);
    }

    #[Test]
    public function it_edit_page_updates_record(): void
    {
        $coupon = Coupon::factory()->create(['coupon_name' => 'Original']);
        Livewire::test(EditCoupon::class, ['record' => $coupon->id])
            ->fillForm(['coupon_name' => 'Updated'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('cart_coupons', ['id' => $coupon->id, 'coupon_name' => 'Updated']);
    }

    #[Test]
    public function it_delete_action_removes_record(): void
    {
        $coupon = Coupon::factory()->create();
        Livewire::test(ListCoupons::class)->callTableAction('delete', $coupon);
        $this->assertDatabaseMissing('cart_coupons', ['id' => $coupon->id]);
    }

    #[Test]
    public function it_can_filter_by_discount_type(): void
    {
        $percentage = Coupon::factory()->create(['discount_type' => 'percentage']);
        $fixed = Coupon::factory()->create(['discount_type' => 'fixed_amount']);

        Livewire::test(ListCoupons::class)
            ->filterTable('discount_type', 'percentage')
            ->assertCanSeeTableRecords([$percentage])
            ->assertCanNotSeeTableRecords([$fixed]);
    }

    #[Test]
    public function it_can_filter_by_is_active(): void
    {
        // Clear existing coupons to ensure clean state
        Coupon::query()->delete();

        $active = Coupon::factory()->create(['is_active' => true]);
        $inactive = Coupon::factory()->create(['is_active' => false]);

        Livewire::test(ListCoupons::class)
            ->filterTable('is_active', true)
            ->assertCanSeeTableRecords([$active])
            ->assertCanNotSeeTableRecords([$inactive]);
    }

    #[Test]
    public function it_sorting_by_column_changes_order(): void
    {
        // Clear existing coupons to ensure clean state
        Coupon::query()->delete();

        // Create coupons with different attributes for sorting
        $couponA = Coupon::factory()->create([
            'coupon_name' => 'AAA Alpha Discount',
            'coupon_code' => 'AAA10',
            'discount_value' => 10,
            'created_at' => now()->subDays(5),
        ]);
        $couponB = Coupon::factory()->create([
            'coupon_name' => 'ZZZ Beta Special',
            'coupon_code' => 'ZZZ20',
            'discount_value' => 20,
            'created_at' => now()->subDays(3),
        ]);
        $couponC = Coupon::factory()->create([
            'coupon_name' => 'ZZZ Charlie Deal',
            'coupon_code' => 'ZZZ30',
            'discount_value' => 30,
            'created_at' => now()->subDays(1),
        ]);

        // Test sorting by coupon_name ascending - AAA should be first
        Livewire::test(ListCoupons::class)
            ->sortTable('coupon_name', 'asc')
            ->assertCanSeeTableRecords([$couponA, $couponB, $couponC], inOrder: true);

        // Test sorting by coupon_name descending - ZZZ should be first
        Livewire::test(ListCoupons::class)
            ->sortTable('coupon_name', 'desc')
            ->assertCanSeeTableRecords([$couponC, $couponB, $couponA], inOrder: true);

        // Test sorting by discount_value descending - 30 should be first
        Livewire::test(ListCoupons::class)
            ->sortTable('discount_value', 'desc')
            ->assertCanSeeTableRecords([$couponC, $couponB, $couponA], inOrder: true);
    }

    #[Test]
    public function it_bulk_delete_removes_selected_records(): void
    {
        $uniqueId = uniqid();
        $coupon1 = Coupon::factory()->create(['coupon_code' => 'BULK' . $uniqueId . '001']);
        $coupon2 = Coupon::factory()->create(['coupon_code' => 'BULK' . $uniqueId . '002']);
        $coupon3 = Coupon::factory()->create(['coupon_code' => 'BULK' . $uniqueId . '003']);

        // Select and bulk delete first two coupons
        Livewire::test(ListCoupons::class)
            ->callTableBulkAction('delete', [$coupon1, $coupon2])
            ->assertHasNoTableBulkActionErrors();

        // Assert deleted records are gone
        $this->assertDatabaseMissing('cart_coupons', ['id' => $coupon1->id]);
        $this->assertDatabaseMissing('cart_coupons', ['id' => $coupon2->id]);

        // Assert third coupon still exists
        $this->assertDatabaseHas('cart_coupons', ['id' => $coupon3->id]);
    }

    #[Test]
    public function it_table_has_required_columns(): void
    {
        Livewire::test(ListCoupons::class)
            ->assertTableColumnExists('coupon_name')
            ->assertTableColumnExists('coupon_code')
            ->assertTableColumnExists('discount_type')
            ->assertTableColumnExists('is_active');
    }
}
