<?php

namespace Modules\Vendors\Services;

use Illuminate\Support\Facades\DB;
use Modules\Product\Models\Product;

/**
 * Sales & catalogue statistics for a single vendor, derived from the
 * order→cart→product(created_by) mapping. One place so the dashboard
 * widgets and tests agree on how a vendor's numbers are computed.
 */
class VendorStats
{
    public function __construct(private int $userId) {}

    public static function for(int $userId): self
    {
        return new self($userId);
    }

    /** @return array<int,int> this vendor's product ids */
    public function productIds(): array
    {
        return Product::query()
            ->filter(['author' => $this->userId])
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    public function productCount(): int
    {
        return count($this->productIds());
    }

    public function activeProductCount(): int
    {
        return Product::query()
            ->filter(['author' => $this->userId])
            ->where('is_active', 1)
            ->where('is_deleted', 0)
            ->count();
    }

    /**
     * Base query over this vendor's completed cart line items.
     *
     * Deliberately NO join to cart_orders: both tables carry `price` and
     * `created_at`, and the DB table prefix is applied to table names but not
     * inside raw SUM() expressions — a join would force fragile qualified raw
     * column names. "Paid" is expressed as a subquery over order ids instead,
     * so every column here is unambiguously a cart column.
     */
    private function lineItems()
    {
        $ids = $this->productIds() ?: [0];
        return DB::table('cart')
            ->whereIn('rel_id', $ids)
            ->where('order_completed', 1);
    }

    /** Line items whose order is paid. */
    private function paidLineItems()
    {
        return $this->lineItems()->whereIn('order_id', function ($q) {
            $q->select('id')->from('cart_orders')->where('is_paid', 1);
        });
    }

    public function orderCount(): int
    {
        return (int) $this->lineItems()->distinct()->count('order_id');
    }

    public function paidOrderCount(): int
    {
        return (int) $this->paidLineItems()->distinct()->count('order_id');
    }

    /** Total paid revenue for this vendor's items. */
    public function revenue(): float
    {
        return (float) $this->paidLineItems()
            ->selectRaw('COALESCE(SUM(price * qty), 0) as t')
            ->value('t');
    }

    public function unitsSold(): int
    {
        return (int) $this->paidLineItems()->sum('qty');
    }

    /**
     * Daily paid revenue for the last $days days (inclusive), as an ordered
     * map 'Y-m-d' => float. Missing days are zero-filled so charts are smooth.
     *
     * @return array<string,float>
     */
    public function revenueByDay(int $days = 30): array
    {
        $start = now()->subDays($days - 1)->startOfDay();

        // Group by the line item's own created_at (stamped with the order), so
        // no join to cart_orders is needed — keeps columns unqualified.
        $rows = $this->paidLineItems()
            ->where('created_at', '>=', $start)
            ->selectRaw('DATE(created_at) as d, COALESCE(SUM(price * qty), 0) as t')
            ->groupBy('d')
            ->pluck('t', 'd');

        $series = [];
        for ($i = 0; $i < $days; $i++) {
            $day = $start->copy()->addDays($i)->format('Y-m-d');
            $series[$day] = (float) ($rows[$day] ?? 0);
        }
        return $series;
    }
}
