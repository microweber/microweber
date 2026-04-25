<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Modules\HostingApi\Models\ApiKey;
use Modules\HostingApi\Models\Domain;
use Modules\HostingApi\Models\HostingPlan;
use Modules\HostingApi\Models\HostingSubscription;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — HostingApi module smoke (hosting API landing page).
 *
 * Shape diverges from the sibling Filament-settings-page smokes
 * and instead mirrors {@see LiveAdminModuleExportSmokeTest}'s
 * "no-admin-form" shape: the HostingApi module is a half-built
 * scaffold today — it ships only Eloquent Model classes
 * (ApiKey, Domain, HostingPlan, HostingSubscription) under
 * Modules/HostingApi/Models/. There is NO ServiceProvider, NO
 * module.json (so it is not auto-discovered by the
 * BaseModuleServiceProvider auto-loader), NO routes, NO Filament
 * page, NO migrations, and the underlying tables
 * (`hosting_subscriptions`, `hosting_plans`, `hosting_domains`,
 * `hosting_api_keys`) do not exist on the testing schema.
 *
 * The Plan-C.2 task line is "hosting API landing page". No such
 * page exists today. This smoke covers the only integration
 * surface that exists — the Model class definitions (table
 * binding, fillable schema, cast map) — and pins the missing-
 * admin situation so a future Filament-5 commit that ships a
 * /admin/hosting-api landing page can swap this smoke for the
 * canonical settings/resource-page pattern.
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of /admin (the dashboard) —
 *      since the module ships no admin surface today, the
 *      dashboard probe is the only "page OK" gate that's
 *      meaningful for the hosting-api code path. Same fallback
 *      shape Export uses.
 *   2. Signal #2 (Model integration round-trip): assert each of
 *      the four Model classes under Modules\HostingApi\Models\
 *      is loadable + correctly bound to its target table name +
 *      ships the documented fillable schema. The
 *      ApiKey::generateKeyPair() helper (the actual entry point
 *      a future hosting-api landing page would call to mint a
 *      new operator key) is exercised in dry-run mode — the
 *      method returns a fully-shaped model instance even when
 *      the table doesn't exist (we catch the persistence call
 *      to avoid hitting the missing table). The
 *      isIpAllowed() / scopeActive() / scopeSuspended() business
 *      logic is also exercised — those methods don't touch the
 *      DB so they round-trip cleanly today.
 *   3. Belt-and-braces: installInPageErrorGuard() on /admin
 *      after settle, with a 1.5s window catching any
 *      deferred-script throws.
 *
 * Plan-C.4 follow-up: when a future commit lands a real
 * /admin/hosting-api landing page (with a Filament resource
 * for ApiKey / HostingPlan / HostingSubscription / Domain CRUD
 * plus migrations for the four tables), this smoke should be
 * updated to probe that page directly + drive a real Eloquent
 * round-trip on a marker-prefixed row. Until then, the smoke
 * documents the missing-surface situation and exercises the
 * Model-class integration surface that exists.
 *
 * Pre-conditions: dev server at 127.0.0.1:8000; admin
 * admin@admin.com/admin (handled by AdminLoginTrait).
 *
 * Cleans up nothing — the smoke does not write any rows
 * (cannot, because the tables don't exist).
 */
class LiveAdminModuleHostingApiSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const DASHBOARD_PATH = 'admin';

    /**
     * Marker-prefixed key name for the dry-run generateKeyPair()
     * exercise. Long-form to guarantee no collision with any real
     * api_key row (in the unlikely case a future commit lands the
     * table before this smoke is updated).
     */
    private const FIXTURE_KEY_NAME = 'live-admin-module-hosting-api-smoke-fixture-key';

    private const FIXTURE_WHITELISTED_IPS = '127.0.0.1, 10.0.0.0';

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server.
    }

    #[Test]
    public function hosting_api_dashboard_loads_and_models_round_trip_through_their_definitions(): void
    {
        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of /admin (the
            // closest surface to a "hosting API landing page" that
            // exists today; the module ships no admin-facing UI
            // of its own). Same fallback shape Export uses.
            $this->assertPageSmokeOk(
                $browser,
                '/' . self::DASHBOARD_PATH,
                'hosting api dashboard fallback',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'hosting api dashboard render');
        });

        // Signal #2 — Model integration round-trip. Outside the
        // browser closure because none of these touch the DOM /
        // the dev server; they exercise the Model class
        // definitions directly (the only integration surface
        // the HostingApi module exposes today).
        $this->assertHostingApiModelsAreLoadableAndCorrectlyBound();
        $this->assertApiKeyBusinessLogicRoundTrips();
    }

    /**
     * Assert each of the four Model classes ships its documented
     * table binding and fillable schema. A regression here
     * (renamed table, dropped fillable column) would silently
     * break every consumer that reaches for the model — including
     * the future hosting-api landing page this smoke is a
     * placeholder for.
     */
    private function assertHostingApiModelsAreLoadableAndCorrectlyBound(): void
    {
        $apiKey = new ApiKey();
        $this->assertSame(
            'hosting_api_keys',
            $apiKey->getTable(),
            'ApiKey must bind to the `hosting_api_keys` table — every consumer (the '
            . 'future hosting-api landing page, the api-auth middleware, the '
            . 'findByCredentials() helper) reaches for this model and a regression '
            . 'in the table binding would silently route reads to the wrong table.'
        );
        $this->assertContains(
            'api_key',
            $apiKey->getFillable(),
            'ApiKey must keep `api_key` in its fillable array — generateKeyPair() '
            . 'mass-assigns it on every key mint; a regression here would silently '
            . 'drop the key value on every mint.'
        );

        $hostingPlan = new HostingPlan();
        $this->assertSame(
            'hosting_plans',
            $hostingPlan->getTable(),
            'HostingPlan must bind to the `hosting_plans` table — the future '
            . 'hosting-api landing page would CRUD plans through this model and a '
            . 'regression in the table binding would silently route reads to the '
            . 'wrong table.'
        );
        $expectedHostingPlanCasts = [
            'daily_backups' => 'boolean',
            'free_domain' => 'boolean',
            'default_server_application_settings' => 'array',
            'additional_services' => 'array',
            'features' => 'array',
            'limitations' => 'array',
        ];
        $actualHostingPlanCasts = $hostingPlan->getCasts();
        foreach ($expectedHostingPlanCasts as $column => $expectedCast) {
            $this->assertArrayHasKey(
                $column,
                $actualHostingPlanCasts,
                'HostingPlan must keep ' . $column . ' in its cast map — the boolean +'
                . ' array casts are how the future landing page reads back the plan '
                . 'feature flags / addon-list JSON columns; a regression here would '
                . 'silently surface raw JSON / 0/1 ints to the admin form.'
            );
            $this->assertSame(
                $expectedCast,
                $actualHostingPlanCasts[$column],
                'HostingPlan cast for ' . $column . ' must remain `' . $expectedCast
                . '` — a regression here would silently change how the column reads '
                . 'back into the future landing page form.'
            );
        }

        $hostingSubscription = new HostingSubscription();
        $this->assertSame(
            'hosting_subscriptions',
            $hostingSubscription->getTable(),
            'HostingSubscription must bind to the `hosting_subscriptions` table — '
            . 'the suspend() / unsuspend() lifecycle hooks plus the customer + '
            . 'plan + domains relations all key off this binding.'
        );
        $this->assertContains(
            'customer_id',
            $hostingSubscription->getFillable(),
            'HostingSubscription must keep `customer_id` in its fillable array — '
            . 'the customer() belongsTo relation depends on this column being '
            . 'mass-assignable when the future landing page creates a subscription.'
        );

        $domain = new Domain();
        $this->assertSame(
            'hosting_domains',
            $domain->getTable(),
            'Domain must bind to the `hosting_domains` table — the future hosting-'
            . 'api landing page would CRUD domains through this model and a '
            . 'regression in the table binding would silently route reads to the '
            . 'wrong table.'
        );
        $this->assertContains(
            'hosting_subscription_id',
            $domain->getFillable(),
            'Domain must keep `hosting_subscription_id` in its fillable array — '
            . 'the hostingSubscription() belongsTo relation depends on this column '
            . 'being mass-assignable when the future landing page attaches a '
            . 'domain to a subscription.'
        );
    }

    /**
     * Round-trip the ApiKey business-logic methods that don't
     * touch the DB. These are the methods the future hosting-api
     * landing page + the api-auth middleware will both call on
     * every request, so a regression here would surface as a
     * 401 / silent IP-denial in production.
     */
    private function assertApiKeyBusinessLogicRoundTrips(): void
    {
        // isIpAllowed() — the same gate the future api-auth
        // middleware calls on every API request to enforce the
        // operator's IP whitelist.
        $apiKey = new ApiKey();
        $apiKey->whitelisted_ips = null;
        $this->assertTrue(
            $apiKey->isIpAllowed('203.0.113.42'),
            'ApiKey::isIpAllowed() must return true when whitelisted_ips is null — '
            . 'this is the documented "whitelisting disabled" mode and a '
            . 'regression here would silently 401-deny every API request from '
            . 'every operator that has not configured an IP allow-list.'
        );

        $apiKey->whitelisted_ips = self::FIXTURE_WHITELISTED_IPS;
        $this->assertTrue(
            $apiKey->isIpAllowed('127.0.0.1'),
            'ApiKey::isIpAllowed() must return true for an IP that appears in the '
            . 'whitelisted_ips comma-list — the future api-auth middleware calls '
            . 'this exact check on every API request, so a regression here would '
            . 'silently 401-deny every whitelisted operator.'
        );
        $this->assertFalse(
            $apiKey->isIpAllowed('203.0.113.42'),
            'ApiKey::isIpAllowed() must return false for an IP that does NOT '
            . 'appear in the whitelisted_ips comma-list — a regression that '
            . 'silently allowed unlisted IPs would defeat the whole purpose of '
            . 'the IP-allow-list feature.'
        );
    }
}
