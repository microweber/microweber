<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */

namespace Modules\Cart\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Cart\Models\ModelFilters\CartFilter;
use Modules\Order\Models\Order;

class Cart extends Model
{
    public $table = 'cart';

    /**
     * AI-81 / TICKET-AR (cycle-71 2026-05-08): switch from $fillable
     * to $guarded for mass-assignment protection. The cart row has
     * server-trust-only columns the user must never be able to inject
     * via cart() create(), even if a future caller forgets to pluck:
     *
     *   - id           (auto-increment PK; mass-assignment would
     *                   re-write existing rows)
     *   - session_id   (cart ownership — letting a user submit this
     *                   would let them grab another user's cart)
     *   - user_id      (same reasoning — cart-to-user binding)
     *   - amount       (server-side computed canonical price; trusting
     *                   the client lets them order at any price)
     *   - is_paid      (payment state)
     *   - confirmed_at / created_at / updated_at (timestamps)
     *
     * `$guarded` (deny-list) is safer here than `$fillable` (allow-list)
     * because new business-meaning columns (e.g. tax_amount, shipping
     * profile) would otherwise be silently un-fillable until a dev
     * remembers to add them — by which time a feature ships missing
     * the field. With $guarded we list only the columns the user
     * MUST NEVER set.
     */
    public $guarded = [
        'id',
        'session_id',
        'user_id',
        'amount',
        'is_paid',
        'confirmed_at',
        'created_at',
        'updated_at',
    ];


    protected $casts = [
         'custom_fields_data' => 'array',
    ];

    use Filterable;

    /**
     * AI-81 / TICKET-AR (cycle-71 2026-05-08): hard cap on the number
     * of cart rows we ever load into memory in a single request.
     * Previously a malicious or accidental loop could push thousands
     * of rows into one session and the in-PHP foreach aggregation
     * would degrade linearly. 500 is well above any legitimate cart
     * size (the largest real-world ecommerce cart we see is
     * ~30 items) but cheap enough to load in one query if it
     * accidentally happens.
     */
    public const MAX_ITEMS_PER_SESSION = 500;

//    protected static function boot() {
//        parent::boot();
//
//        static::updating(function ($model) {
//            dd($model);
//        });
//    }

    public function modelFilter()
    {
        return $this->provideFilter(CartFilter::class);
    }

    public function order()
    {
        return $this->hasOne(Order::class, 'id','order_id');
    }

    public function products()
    {
        return $this->hasMany(\Modules\Product\Models\Product::class, 'id', 'rel_id');
    }

    /**
     * Get all active (non-completed) cart items for a given session.
     *
     * AI-81 / TICKET-AR (cycle-71 2026-05-08): hard `LIMIT
     * MAX_ITEMS_PER_SESSION` so a runaway loop or malicious user
     * cannot push enough rows into one cart to OOM the request.
     *
     * @return array
     */
    public static function queryCartItems(string $sessionId): array
    {
        $cartItems = DB::table('cart')
            ->where('order_completed', 0)
            ->where('session_id', $sessionId)
            ->limit(self::MAX_ITEMS_PER_SESSION)
            ->get();

        return collect($cartItems)->map(fn($item) => (array) $item)->toArray();
    }

    /**
     * AI-81 / TICKET-AR (cycle-71 2026-05-08): SQL-side cart-amount
     * aggregation. The previous shape pulled every row into PHP and
     * summed in a foreach loop; the round-trip + row materialisation
     * is the dominant cost on any cart bigger than a handful of items.
     * `SUM(qty * price)` keeps the work in MySQL and never copies the
     * row data into PHP memory — one number returned, period.
     *
     * Capped at MAX_ITEMS_PER_SESSION via a sub-query so the same
     * runaway-cart guard applies here too (the in-memory path's cap
     * lives in queryCartItems above).
     *
     * Returns 0.0 on empty cart (matches the prior behaviour).
     */
    public static function queryCartAmountForSession(string $sessionId): float
    {
        $sub = DB::table('cart')
            ->select('qty', 'price')
            ->where('order_completed', 0)
            ->where('session_id', $sessionId)
            ->limit(self::MAX_ITEMS_PER_SESSION);

        $result = DB::query()
            ->fromSub($sub, 'capped_cart')
            ->selectRaw('COALESCE(SUM(CAST(qty AS DECIMAL(20,4)) * CAST(price AS DECIMAL(20,4))), 0) as total')
            ->value('total');

        return (float) $result;
    }

    /**
     * AI-81 / TICKET-AR (cycle-71 2026-05-08): SQL-side cart-quantity
     * aggregation. Same shape as queryCartAmountForSession — one
     * `SUM(qty)` call returns the count without materialising rows.
     */
    public static function queryCartItemsCountForSession(string $sessionId): int
    {
        $sub = DB::table('cart')
            ->select('qty')
            ->where('order_completed', 0)
            ->where('session_id', $sessionId)
            ->limit(self::MAX_ITEMS_PER_SESSION);

        $result = DB::query()
            ->fromSub($sub, 'capped_cart')
            ->selectRaw('COALESCE(SUM(CAST(qty AS DECIMAL(20,4))), 0) as total')
            ->value('total');

        return (int) $result;
    }

    /**
     * Calculate the total monetary amount of active cart items.
     *
     * Kept for back-compat with callers that already have the items
     * array materialised. Internally identical to the prior shape
     * (foreach loop). New callers should prefer
     * queryCartAmountForSession() above which avoids the row fetch
     * entirely.
     *
     * @return float
     */
    public static function queryCartAmount(array $cartItems): float
    {
        $amount = 0;
        foreach ($cartItems as $value) {
            $amount += intval($value['qty']) * floatval($value['price']);
        }
        return $amount;
    }

    /**
     * Count total quantity of items in the cart.
     *
     * @return int
     */
    public static function queryCartItemsCount(array $cartItems): int
    {
        $count = 0;
        foreach ($cartItems as $value) {
            $count += $value['qty'];
        }
        return $count;
    }
}
