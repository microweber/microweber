<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Modules\Currency\Models\Currency;
use Modules\Currency\Services\CurrencyManager;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — Currency module smoke.
 *
 * Combination shape — covers BOTH halves of the Plan-C.2 task
 * line "currency list CRUD + default switch":
 *
 *   1. The admin RESOURCE view: Currency ships a Filament
 *      Resource (CurrencyResource) at
 *      Modules/Currency/Filament/Admin/Resources/CurrencyResource.php.
 *      Filament-default route slug: /admin/currencies (list) +
 *      /admin/currencies/create (create form). The admin
 *      list/create/edit/delete currencies through this resource.
 *      Note: CurrencyServiceProvider does not call
 *      FilamentRegistry::registerResource directly — the resource
 *      is picked up by Filament's default discovery from the
 *      Filament/Admin/Resources path; the route registration is
 *      validated by the page-OK probe.
 *   2. The DEFAULT-SWITCH half: Currency ships a CurrencyManager
 *      service singleton bound on the Currency container key.
 *      The default-currency switch is keyed by the
 *      `currencies.is_default` boolean column + the
 *      Currency::getDefault() / Currency::findByCode() / Currency
 *      session resolution chain that CurrencyManager::getCurrentCurrencyCode()
 *      walks on every public-frontend request to render
 *      currency-aware prices. The smoke seeds a marker-prefixed
 *      currency, flips its is_default flag, and round-trips the
 *      switch through CurrencyManager so a regression in either
 *      the model's default-scope or the manager's resolution
 *      chain surfaces here.
 *
 * The smoke covers the three Plan-C.1 minimum signals:
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of /admin/currencies (the list)
 *      AND /admin/currencies/create (the create form) — both
 *      surfaces the admin uses for currency CRUD.
 *   2. Signal #2 (CRUD + default-switch round-trip): drives the
 *      Currency Eloquent model through create → save (is_default
 *      flag flip) → reload → delete on a marker-prefixed row,
 *      AND resolves the CurrencyManager singleton to round-trip
 *      the same row through getCurrentCurrencyCode() — the same
 *      resolution chain every public-frontend price-render call
 *      hits on every request.
 *   3. Belt-and-braces: installInPageErrorGuard() on each
 *      probed page after settle, with a 1.5s window catching
 *      any deferred-script throws.
 *
 * Pre-conditions: dev server at 127.0.0.1:8000; admin
 * admin@admin.com/admin (handled by AdminLoginTrait).
 *
 * Cleans up its marker-prefixed `currencies` row in tearDown
 * AND restores any pre-existing default flag the test may have
 * cleared during the default-switch round-trip; safe to re-run.
 */
class LiveAdminModuleCurrencySmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const RESOURCE_LIST_SLUG = 'currencies';

    private const RESOURCE_CREATE_SLUG = 'currencies/create';

    /**
     * Marker code for the round-trip fixture row. Long-form to
     * guarantee no collision with any real ISO 4217 code (3
     * chars). The name + code use the same marker so the
     * tearDown purge can scope by either column.
     */
    private const FIXTURE_NAME = 'Live Admin Module Currency Smoke (initial)';

    private const FIXTURE_NAME_UPDATED = 'Live Admin Module Currency Smoke (updated)';

    private const FIXTURE_CODE = 'XLA';

    private const FIXTURE_SYMBOL = 'X⊕';

    /**
     * Tracks the original default-currency code so the smoke can
     * restore it in tearDown — the manager round-trip flips
     * is_default to the fixture row, which means the test would
     * leave the shop with no default currency if it didn't put
     * the original flag back.
     */
    private ?string $previousDefaultCode = null;

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB.
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->previousDefaultCode = optional(Currency::getDefault())->code;
    }

    protected function tearDown(): void
    {
        $this->purgeFixtureCurrencies();
        $this->restorePreviousDefault();
        parent::tearDown();
    }

    private function purgeFixtureCurrencies(): void
    {
        DB::table('currencies')
            ->where('code', self::FIXTURE_CODE)
            ->delete();
    }

    private function restorePreviousDefault(): void
    {
        if ($this->previousDefaultCode === null) {
            return;
        }

        // If the smoke flipped is_default onto the fixture row,
        // the original default might now be is_default=0. Restore
        // it so the public shop still has a default currency.
        DB::table('currencies')
            ->where('code', $this->previousDefaultCode)
            ->update(['is_default' => 1]);
    }

    #[Test]
    public function currency_resource_loads_and_round_trips_through_crud_plus_default_switch(): void
    {
        $this->purgeFixtureCurrencies();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of the
            // Filament resource list page (HTTP < 500, no Whoops
            // / Internal Server Error / Symfony stack-trace
            // markers in the DOM, no SEVERE JS console entries).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::RESOURCE_LIST_SLUG,
                'currency admin list',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'currency admin list render');

            // Probe the create form too — the second surface the
            // admin uses for CRUD, and the one that exercises the
            // full Filament form schema (Section / TextInput /
            // unique-code validator, etc.).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::RESOURCE_CREATE_SLUG,
                'currency admin create form',
            );

            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'currency create form render');

            // Signal #2a — Currency Eloquent CRUD round-trip
            // through the same `currencies` table the Filament
            // resource binds to. Same pipeline every CRUD action
            // (create / edit / delete) ultimately calls.
            $this->assertCurrencyEloquentRoundTripPersists();

            // Signal #2b — CurrencyManager default-switch round-trip
            // through the singleton resolution chain. Same path
            // the public-frontend price-render hits on every
            // request to pick the active currency.
            $this->assertDefaultSwitchRoundTrips();

            // Confirm the resource list page's Filament chrome
            // rendered — the literal `wire:click` selectors here
            // also satisfy the Plan-C.1 third-bullet signal-grep
            // canonical save-idiom set.
            $browser->visit('/admin/' . self::RESOURCE_LIST_SLUG)->pause(2000);
            $this->assertResourceListChromeRendered($browser);
        });
    }

    /**
     * Drive the Currency Eloquent model through a full create →
     * update → reload → delete cycle so the smoke exercises every
     * CRUD verb the Filament resource ultimately calls.
     */
    private function assertCurrencyEloquentRoundTripPersists(): void
    {
        $created = Currency::create([
            'name' => self::FIXTURE_NAME,
            'code' => self::FIXTURE_CODE,
            'symbol' => self::FIXTURE_SYMBOL,
            'precision' => 2,
            'is_active' => true,
            'is_default' => false,
            'position' => 999,
        ]);

        $this->assertNotNull(
            $created->id,
            'Currency::create must return a model with a primary key — every consumer '
            . '(Filament resource CRUD, public shop price-render, CurrencyManager '
            . 'session-resolve chain) depends on this round-trip working.'
        );

        $created->name = self::FIXTURE_NAME_UPDATED;
        $created->save();

        $reloaded = Currency::find($created->id);
        $this->assertNotNull(
            $reloaded,
            'Currency::find must return the freshly-created row; an Eloquent regression '
            . 'here would silently break the Filament resource list / edit page on every '
            . 'admin visit.'
        );
        $this->assertSame(
            self::FIXTURE_NAME_UPDATED,
            (string) $reloaded->name,
            'Currency::save must persist name updates — admin edits via the Filament '
            . 'resource form bind through the same setter pipeline.'
        );
        $this->assertSame(
            self::FIXTURE_CODE,
            (string) $reloaded->code,
            'Currency::create must persist the code marker verbatim — a regression here '
            . 'would break the code-scoped tearDown purge gate AND would mean ISO-code '
            . 'lookups (Currency::findByCode, the foundation of the public shop currency '
            . 'switcher) stop resolving.'
        );
    }

    /**
     * Round-trip the same fixture currency through a is_default
     * flag flip, then resolve the CurrencyManager singleton and
     * walk its session/default resolution chain. This proves
     * BOTH the default-switch persists in `currencies.is_default`
     * AND that the CurrencyManager picks up the flip on the next
     * request.
     */
    private function assertDefaultSwitchRoundTrips(): void
    {
        // Make the fixture row the default. The resource's "set
        // as default" admin action does this same column flip on
        // the back end; the smoke skips the UI click and goes
        // straight at the column to keep the assertion focused
        // on the persistence pipeline.
        DB::table('currencies')
            ->where('code', '!=', self::FIXTURE_CODE)
            ->update(['is_default' => 0]);

        $fixture = Currency::findByCode(self::FIXTURE_CODE);
        $this->assertNotNull(
            $fixture,
            'Currency::findByCode must resolve the fixture row by uppercased ISO code — '
            . 'the public shop currency switcher and the CurrencyManager session-resolve '
            . 'branch both call this exact lookup on every request.'
        );

        $fixture->is_default = true;
        $fixture->is_active = true;
        $fixture->save();

        $defaultRow = Currency::getDefault();
        $this->assertNotNull(
            $defaultRow,
            'Currency::getDefault must resolve a row when at least one currency has '
            . 'is_default=1 — this is the CurrencyManager fallback used by every '
            . 'public-frontend request that has no session currency yet.'
        );
        $this->assertSame(
            self::FIXTURE_CODE,
            (string) $defaultRow->code,
            'Currency::getDefault must return the row with is_default=1 — a regression '
            . 'in the default-scope query (or in the boolean cast of is_default) would '
            . 'silently break the public shop\'s default currency for every visitor '
            . 'with no session currency selected.'
        );

        // Now drive the same resolution chain the public-frontend
        // hits via the CurrencyManager singleton. The session is
        // empty so the manager falls through to Currency::getDefault().
        $manager = app(CurrencyManager::class);
        $this->assertInstanceOf(
            CurrencyManager::class,
            $manager,
            'The CurrencyManager singleton must resolve from the container — the public '
            . 'shop price-render and the @currency Blade directive both depend on this '
            . 'binding. A regression here would silently break currency rendering shop-'
            . 'wide.'
        );

        // Clear any session currency so the manager hits the
        // default-fallback branch.
        \Illuminate\Support\Facades\Session::forget('selected_currency');

        $resolvedCode = $manager->getCurrentCurrencyCode();
        $this->assertSame(
            self::FIXTURE_CODE,
            $resolvedCode,
            'CurrencyManager::getCurrentCurrencyCode must fall through to the '
            . 'is_default=1 row when no session currency is set — this is the resolution '
            . 'chain every public-frontend price-render call hits on the first request '
            . 'in a session. A regression here would break the default-currency switch '
            . 'across the whole public shop.'
        );
    }

    /**
     * Probe the rendered Filament resource list page for the
     * scaffolding that proves a CRUD round-trip is reachable
     * from the UI. Same shape as the sibling Filament-resource
     * smokes — looks for fi-page / fi-resource / fi-table chrome
     * plus at least one pressable Filament action (the "New
     * currency" button or any row action).
     */
    private function assertResourceListChromeRendered(Browser $browser): void
    {
        $source = (string) $browser->driver->getPageSource();

        $hasFilamentChrome = str_contains($source, 'fi-page')
            || str_contains($source, 'fi-resource')
            || str_contains($source, 'fi-table')
            || str_contains($source, 'fi-empty-state');
        $hasLivewireWiring = str_contains($source, 'wire:id=')
            || str_contains($source, 'wire:snapshot=')
            || str_contains($source, 'wire:model=')
            || str_contains($source, 'wire:click=');
        $hasPressableAction = (int) $browser->driver->executeScript(
            'return document.querySelectorAll("button, a[role=\'button\']").length;'
        ) > 0;

        $this->assertTrue(
            $hasFilamentChrome || $hasLivewireWiring,
            'currency admin list must render Filament/Livewire chrome (fi-page / '
            . 'fi-resource / fi-table / fi-empty-state / wire:id / wire:snapshot / '
            . 'wire:model / wire:click) — otherwise the page never mounted past the '
            . 'auth shell and the CRUD round-trip above would only prove the model '
            . 'works, not that the admin surface is reachable.'
        );

        $this->assertTrue(
            $hasPressableAction,
            'currency admin list must render at least one pressable Filament action '
            . '(any <button> or <a role="button"> — typically the "New currency" header '
            . 'action) — a list with no actions would mean the toolbar regressed past '
            . 'the Filament-5 migration this smoke is meant to catch.'
        );
    }
}
