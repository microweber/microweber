<?php

namespace Modules\Coupons\Tests\Unit\Filament;

use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Coupons\Filament\Resources\CouponResource;
use Modules\Coupons\Filament\Resources\CouponResource\Pages\ListCoupons;
use Modules\Coupons\Filament\Resources\CouponResource\Pages\CreateCoupon;
use Modules\Coupons\Filament\Resources\CouponResource\Pages\EditCoupon;
use Modules\Coupons\Models\Coupon;
use MicroweberPackages\User\Models\User;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CouponResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAsAdmin();
        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    protected function actingAsAdmin(): User
    {
        $user = User::factory()->create(['is_admin' => 1]);
        $this->actingAs($user);
        return $user;
    }

    #[Test]
    public function test_index_page_loads_without_errors(): void
    {
        Livewire::test(ListCoupons::class)->assertSuccessful();
    }

    #[Test]
    public function test_index_page_shows_all_records(): void
    {
        $coupons = Coupon::factory()->count(3)->create();
        Livewire::test(ListCoupons::class)->assertCanSeeTableRecords($coupons);
    }

    #[Test]
    public function test_create_page_saves_new_record(): void
    {
        Livewire::test(CreateCoupon::class)
            ->fillForm([
                'coupon_name' => 'Test Coupon',
                'coupon_code' => 'TEST10',
                'discount_type' => 'percentage',
                'discount_value' => 10,
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('coupons', ['coupon_name' => 'Test Coupon', 'coupon_code' => 'TEST10']);
    }

    #[Test]
    public function test_edit_page_updates_record(): void
    {
        $coupon = Coupon::factory()->create(['coupon_name' => 'Original']);
        Livewire::test(EditCoupon::class, ['record' => $coupon->id])
            ->fillForm(['coupon_name' => 'Updated'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('coupons', ['id' => $coupon->id, 'coupon_name' => 'Updated']);
    }

    #[Test]
    public function test_delete_action_removes_record(): void
    {
        $coupon = Coupon::factory()->create();
        Livewire::test(ListCoupons::class)->callTableAction('delete', $coupon);
        $this->assertDatabaseMissing('coupons', ['id' => $coupon->id]);
    }

    #[Test]
    public function test_can_filter_by_discount_type(): void
    {
        $percentage = Coupon::factory()->create(['discount_type' => 'percentage']);
        $fixed = Coupon::factory()->create(['discount_type' => 'fixed_amount']);

        Livewire::test(ListCoupons::class)
            ->filterTable('discount_type', 'percentage')
            ->assertCanSeeTableRecords([$percentage])
            ->assertCanNotSeeTableRecords([$fixed]);
    }

    #[Test]
    public function test_can_filter_by_is_active(): void
    {
        $active = Coupon::factory()->create(['is_active' => true]);
        $inactive = Coupon::factory()->create(['is_active' => false]);

        Livewire::test(ListCoupons::class)
            ->filterTable('is_active', true)
            ->assertCanSeeTableRecords([$active])
            ->assertCanNotSeeTableRecords([$inactive]);
    }

    #[Test]
    public function test_sorting_by_column_changes_order(): void
    {
        // Create coupons with different attributes for sorting
        $couponA = Coupon::factory()->create([
            'coupon_name' => 'Alpha Discount',
            'coupon_code' => 'ALPHA10',
            'discount_value' => 10,
            'created_at' => now()->subDays(5),
        ]);
        $couponB = Coupon::factory()->create([
            'coupon_name' => 'Beta Special',
            'coupon_code' => 'BETA20',
            'discount_value' => 20,
            'created_at' => now()->subDays(3),
        ]);
        $couponC = Coupon::factory()->create([
            'coupon_name' => 'Charlie Deal',
            'coupon_code' => 'CHARLIE30',
            'discount_value' => 30,
            'created_at' => now()->subDays(1),
        ]);

        // Test sorting by coupon_name ascending
        Livewire::test(ListCoupons::class)
            ->sortTable('coupon_name', 'asc')
            ->assertCanSeeTableRecords([$couponA, $couponB, $couponC], inOrder: true);

        // Test sorting by discount_value descending
        Livewire::test(ListCoupons::class)
            ->sortTable('discount_value', 'desc')
            ->assertCanSeeTableRecords([$couponC, $couponB, $couponA], inOrder: true);

        // Test sorting by created_at descending
        Livewire::test(ListCoupons::class)
            ->sortTable('created_at', 'desc')
            ->assertCanSeeTableRecords([$couponC, $couponB, $couponA], inOrder: true);
    }

    #[Test]
    public function test_bulk_delete_removes_selected_records(): void
    {
        $coupon1 = Coupon::factory()->create(['coupon_code' => 'BULK001']);
        $coupon2 = Coupon::factory()->create(['coupon_code' => 'BULK002']);
        $coupon3 = Coupon::factory()->create(['coupon_code' => 'BULK003']);

        // Select and bulk delete first two coupons
        Livewire::test(ListCoupons::class)
            ->callTableBulkAction('delete', [$coupon1, $coupon2])
            ->assertHasNoTableBulkActionErrors();

        // Assert deleted records are gone
        $this->assertDatabaseMissing('coupons', ['id' => $coupon1->id]);
        $this->assertDatabaseMissing('coupons', ['id' => $coupon2->id]);

        // Assert third coupon still exists
        $this->assertDatabaseHas('coupons', ['id' => $coupon3->id]);
    }

    #[Test]
    public function test_table_has_required_columns(): void
    {
        Livewire::test(ListCoupons::class)
            ->assertTableColumnExists('coupon_name')
            ->assertTableColumnExists('coupon_code')
            ->assertTableColumnExists('discount_type')
            ->assertTableColumnExists('is_active');
    }
}
