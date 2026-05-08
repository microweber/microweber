<?php

declare(strict_types=1);

namespace Tests\Feature;

use Modules\Cart\Models\Cart;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-71 / AI-81 / TICKET-AR — Cart perf hardening regression
 * coverage.
 *
 * Pins:
 *   - Cart model uses $guarded (not $fillable) so server-trust-only
 *     columns (id, session_id, user_id, amount, is_paid,
 *     confirmed_at, timestamps) cannot be mass-assigned by user
 *     input. Defence-in-depth — current callers are correct, but
 *     this closes the door on future drift.
 *   - MAX_ITEMS_PER_SESSION constant is set (500) and applied as a
 *     LIMIT in queryCartItems and the new SQL-side aggregators.
 *   - New SQL-side aggregators queryCartAmountForSession() and
 *     queryCartItemsCountForSession() exist with the canonical
 *     signature (string $sessionId): float|int.
 *   - CartRepository falls back to the SQL-side aggregator when
 *     no cart items are already cached — turns three full-row
 *     fetches into one aggregated query for the empty / cold-cache
 *     case.
 *
 * Style after the cycle-52..70 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class CartPerfGuardedSqlAggregationContractTest extends TestCase
{
    private string $modelSrc;
    private string $repoSrc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->modelSrc = file_get_contents(base_path(
            'Modules/Cart/Models/Cart.php'
        ));
        $this->repoSrc = file_get_contents(base_path(
            'Modules/Cart/Repositories/CartRepository.php'
        ));
    }

    #[Test]
    public function cart_model_uses_guarded_not_fillable(): void
    {
        // The previous shape (allow-list via $fillable) is gone — the
        // new shape uses $guarded with the server-trust-only columns.
        $this->assertStringContainsString(
            'public $guarded = [',
            $this->modelSrc,
            'Cart model: must use $guarded (deny-list) for mass-assignment protection'
        );

        // Required deny-list members per the brief.
        $required = [
            "'id'",
            "'session_id'",
            "'user_id'",
            "'amount'",
            "'is_paid'",
            "'confirmed_at'",
            "'created_at'",
            "'updated_at'",
        ];
        foreach ($required as $col) {
            $this->assertStringContainsString(
                $col,
                $this->modelSrc,
                "Cart model: \$guarded must include {$col} so users cannot mass-assign it"
            );
        }

        // The old $fillable shape is gone — replacing it with
        // $guarded is the whole point. Pin the negative.
        $this->assertStringNotContainsString(
            'public $fillable = [',
            $this->modelSrc,
            'Cart model: $fillable must be replaced with $guarded'
        );
    }

    #[Test]
    public function max_items_per_session_constant_is_set_and_used(): void
    {
        // Constant declared on the class.
        $this->assertSame(
            500,
            Cart::MAX_ITEMS_PER_SESSION,
            'Cart model: MAX_ITEMS_PER_SESSION must equal 500 — well above any legitimate cart size, cheap to load'
        );

        // Used as a LIMIT in queryCartItems.
        $this->assertMatchesRegularExpression(
            '/queryCartItems[^}]+->limit\\(self::MAX_ITEMS_PER_SESSION\\)/s',
            $this->modelSrc,
            'Cart model: queryCartItems must apply ->limit(self::MAX_ITEMS_PER_SESSION)'
        );

        // The new SQL aggregators must apply the same cap (via sub-query
        // because LIMIT inside an aggregate is otherwise ignored).
        $this->assertMatchesRegularExpression(
            '/queryCartAmountForSession[^}]+->limit\\(self::MAX_ITEMS_PER_SESSION\\)/s',
            $this->modelSrc,
            'Cart model: queryCartAmountForSession must cap at MAX_ITEMS_PER_SESSION too'
        );
        $this->assertMatchesRegularExpression(
            '/queryCartItemsCountForSession[^}]+->limit\\(self::MAX_ITEMS_PER_SESSION\\)/s',
            $this->modelSrc,
            'Cart model: queryCartItemsCountForSession must cap at MAX_ITEMS_PER_SESSION too'
        );
    }

    #[Test]
    public function sql_side_aggregators_exist_with_canonical_signatures(): void
    {
        // Pin the new method names + signatures so a future refactor
        // can't silently rename them and break the repository's fast
        // path.
        $this->assertStringContainsString(
            'public static function queryCartAmountForSession(string $sessionId): float',
            $this->modelSrc,
            'Cart model: queryCartAmountForSession(string $sessionId): float must exist'
        );
        $this->assertStringContainsString(
            'public static function queryCartItemsCountForSession(string $sessionId): int',
            $this->modelSrc,
            'Cart model: queryCartItemsCountForSession(string $sessionId): int must exist'
        );

        // Both must do their work in SQL (selectRaw with SUM), not in
        // a foreach loop after fetch.
        $this->assertMatchesRegularExpression(
            '/queryCartAmountForSession[^}]+selectRaw\\([^)]*SUM\\(/s',
            $this->modelSrc,
            'Cart model: queryCartAmountForSession must aggregate via selectRaw(SUM(...))'
        );
        $this->assertMatchesRegularExpression(
            '/queryCartItemsCountForSession[^}]+selectRaw\\([^)]*SUM\\(/s',
            $this->modelSrc,
            'Cart model: queryCartItemsCountForSession must aggregate via selectRaw(SUM(...))'
        );

        // Empty-cart safety: COALESCE so SUM never returns null.
        $this->assertMatchesRegularExpression(
            '/queryCartAmountForSession[^}]+COALESCE\\(SUM\\(/s',
            $this->modelSrc,
            'Cart model: queryCartAmountForSession must wrap SUM in COALESCE so empty carts return 0 not null'
        );
    }

    #[Test]
    public function cart_repository_falls_back_to_sql_aggregator_on_cold_cache(): void
    {
        // The fast path is "use the already-cached items array if it's
        // hot"; the cold-cache path must call the new SQL-side
        // aggregator instead of materialising every row.
        $this->assertStringContainsString(
            'Cart::queryCartAmountForSession($sid)',
            $this->repoSrc,
            'CartRepository::getCartAmount() must call queryCartAmountForSession on cold cache'
        );
        $this->assertStringContainsString(
            'Cart::queryCartItemsCountForSession($sid)',
            $this->repoSrc,
            'CartRepository::getCartItemsCount() must call queryCartItemsCountForSession on cold cache'
        );

        // Hot-path guard: when items are already cached, use the
        // in-memory aggregator so we don't run a second query.
        $this->assertStringContainsString(
            'is_array($cartItems) && !empty($cartItems)',
            $this->repoSrc,
            'CartRepository: must check is_array && !empty before using the in-memory aggregator'
        );
        // Ordering guard: the hot-path return must come BEFORE the
        // cold-path SQL call. Otherwise both paths run on hot cache,
        // negating the point.
        $hotPos = strpos($this->repoSrc, 'return Cart::queryCartAmount(');
        $coldPos = strpos($this->repoSrc, 'queryCartAmountForSession');
        $this->assertNotFalse($hotPos);
        $this->assertNotFalse($coldPos);
        $this->assertLessThan(
            $coldPos,
            $hotPos,
            'CartRepository::getCartAmount(): hot-path return must precede the cold-path SQL call'
        );
    }
}
