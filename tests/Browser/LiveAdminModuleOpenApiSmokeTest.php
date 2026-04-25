<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — OpenApi module smoke (OpenAPI docs route).
 *
 * Non-Filament shape: the OpenApi module wires the L5Swagger
 * service provider in OpenApiServiceProvider.php and serves the
 * Swagger UI at /api/documentation plus the spec JSON at the
 * vendor-default `/docs?api-docs.json` query (see
 * Modules/OpenApi/config/l5-swagger.php — `routes.api` =
 * "api/documentation", `routes.docs` = "docs",
 * `paths.docs_json` = "api-docs.json"). Both surfaces are the
 * public/admin entry points operators consult to read the
 * headless `/api/module/*` REST contract.
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of /api/documentation — the
 *      Swagger UI shell that operators load to browse the spec.
 *   2. Signal #2 (spec round-trip): GET /docs?api-docs.json
 *      directly, parse the response as JSON, and assert the
 *      OpenAPI envelope (openapi version, info.title, paths) is
 *      well-formed. A regression in the L5Swagger boot pipeline
 *      (broken scan paths, deep-merge regression in the
 *      app->booting closure that overwrites the vendor config)
 *      surfaces here as either an HTTP error or an empty / mis-
 *      shaped JSON envelope.
 *   3. Belt-and-braces: installInPageErrorGuard() on the
 *      Swagger UI page after settle, with a 1.5s window
 *      catching any deferred-script throws from the bundled
 *      swagger-ui JS.
 *
 * Pre-conditions: dev server at 127.0.0.1:8000; admin
 * admin@admin.com/admin (handled by AdminLoginTrait). The Swagger
 * UI route does not enforce admin auth in the default Microweber
 * config — but loginAsAdmin keeps the smoke aligned with the
 * sibling Plan-C.2 tests so any future auth-gating change is
 * detected automatically.
 *
 * Read-only — exercises only GET routes, no fixture rows to
 * clean up. Safe to re-run.
 */
class LiveAdminModuleOpenApiSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const SWAGGER_UI_PATH = '/api/documentation';

    /**
     * The default L5Swagger UI shell renders a query-string-
     * suffixed JSON spec URL — see the bundled `index.blade.php`
     * `urls.push({name: …, url: "{baseUrl}/docs?{docs_json}"})`
     * snippet at Modules/OpenApi/config/l5-swagger.php's
     * defaults.api.urls.0 hook. The sniff route below mirrors
     * that shape so this smoke fails the moment the module's
     * deep-merge override stops winning over the vendor default.
     */
    private const SPEC_JSON_PATH = '/docs?api-docs.json';

    private const EXPECTED_OPENAPI_VERSION_PREFIX = '3.0';

    private const EXPECTED_TITLE_FRAGMENT = 'Microweber';

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server.
    }

    #[Test]
    public function open_api_docs_ui_loads_and_round_trips_a_well_formed_spec_envelope(): void
    {
        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of the Swagger
            // UI shell (HTTP < 500, no Whoops / Internal Server
            // Error / Symfony stack-trace markers in the DOM, no
            // SEVERE JS console entries).
            $this->assertPageSmokeOk(
                $browser,
                self::SWAGGER_UI_PATH,
                'OpenAPI Swagger UI',
            );

            // Belt-and-braces console probe after a settle window
            // — the bundled swagger-ui bootstrap loads the JSON
            // spec asynchronously, so a regression in the spec
            // route surfaces here as a deferred SEVERE log even
            // when the initial page render is clean.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'OpenAPI Swagger UI render');

            // Confirm the page rendered the swagger-ui chrome
            // (the #swagger-ui mount node + the bundled CSS link)
            // — without it, the no-console-errors gate above
            // would only prove the page didn't throw, not that
            // the UI shell mounted.
            $this->assertSwaggerUiChromeRendered($browser);
        });

        // Signal #2 — round-trip the spec JSON directly through
        // the L5Swagger docs route. A regression in the
        // app->booting deep-merge override
        // (OpenApiServiceProvider.php) would either flip the
        // route off entirely (404) or return a vendor-default
        // envelope that drops the Microweber-overridden title.
        $this->assertSpecJsonEnvelopeIsWellFormed();
    }

    /**
     * GET the OpenAPI spec JSON over HTTP and assert the envelope
     * is well-formed: HTTP 200, parseable JSON, OpenAPI 3.x
     * version, Microweber-overridden title, non-empty paths map.
     * The test runs against the live dev server at 127.0.0.1:8000
     * (same host the Dusk browser uses) so it exercises the same
     * boot pipeline a real admin browser hits.
     */
    private function assertSpecJsonEnvelopeIsWellFormed(): void
    {
        $url = 'http://127.0.0.1:8000' . self::SPEC_JSON_PATH;

        $context = stream_context_create([
            'http' => [
                'timeout' => 10,
                'ignore_errors' => true,
                'header' => "Accept: application/json\r\n",
            ],
        ]);

        $body = @file_get_contents($url, false, $context);

        $this->assertNotFalse(
            $body,
            'GET ' . self::SPEC_JSON_PATH . ' must return a response body — a regression '
            . 'in the L5Swagger route registration (vendor config drift, broken scan '
            . 'paths in OpenApiServiceProvider::register()) would surface here as a '
            . 'connect/timeout failure with no body to parse.'
        );

        $statusLine = $http_response_header[0] ?? '';
        $this->assertStringContainsString(
            ' 200 ',
            $statusLine,
            'GET ' . self::SPEC_JSON_PATH . ' must return HTTP 200 — anything else means '
            . 'either the L5Swagger route stopped registering or the spec scan failed at '
            . 'boot. Either regression would silently break every consumer of the '
            . 'headless /api/module/* docs.'
        );

        $envelope = json_decode($body, true);
        $this->assertIsArray(
            $envelope,
            'GET ' . self::SPEC_JSON_PATH . ' must return a JSON-decodable response — a '
            . 'regression in the deep-merge override (OpenApiServiceProvider::register '
            . '`app->booting` closure) would flip the body to either HTML (Whoops) or '
            . 'an empty string, both of which json_decode would reject here.'
        );

        $this->assertArrayHasKey(
            'openapi',
            $envelope,
            'OpenAPI spec envelope must declare an `openapi` version — every Swagger '
            . 'UI / API client decides protocol behaviour from this string. A spec '
            . 'without it is not a valid OpenAPI document and would silently break '
            . 'downstream code-generators.'
        );
        $this->assertStringStartsWith(
            self::EXPECTED_OPENAPI_VERSION_PREFIX,
            (string) $envelope['openapi'],
            'OpenAPI version must start with `' . self::EXPECTED_OPENAPI_VERSION_PREFIX
            . '` — a major-version bump (4.x) would silently break every consumer that '
            . 'parses the spec against a 3.x schema validator.'
        );

        $this->assertArrayHasKey(
            'info',
            $envelope,
            'OpenAPI spec must include an `info` section — Swagger UI reads this to '
            . 'render the page header. A regression that drops the section would mean '
            . 'the deep-merge override in OpenApiServiceProvider has stopped winning.'
        );
        $this->assertStringContainsString(
            self::EXPECTED_TITLE_FRAGMENT,
            (string) ($envelope['info']['title'] ?? ''),
            'OpenAPI spec info.title must include `' . self::EXPECTED_TITLE_FRAGMENT
            . '` — this string only appears when the module-level config '
            . '(Modules/OpenApi/config/l5-swagger.php) wins over the vendor default '
            . 'via the array_replace_recursive deep-merge in '
            . 'OpenApiServiceProvider::register(). A regression here means the override '
            . 'broke and operators are seeing the vendor placeholder title instead.'
        );

        $this->assertArrayHasKey(
            'paths',
            $envelope,
            'OpenAPI spec must include a `paths` map — without it Swagger UI renders '
            . 'an empty page and every API consumer sees zero endpoints. A regression '
            . 'in the headless /api/module/* annotation scan paths would surface here.'
        );
        $this->assertNotEmpty(
            $envelope['paths'],
            'OpenAPI spec `paths` map must be non-empty — the module restricts the '
            . 'scan to the headless /api/module/* controllers (see '
            . 'Modules/OpenApi/config/l5-swagger.php paths.annotations) and at least '
            . 'one annotated controller exists. An empty paths map means the scan '
            . 'either failed silently or the annotation source list got rewired '
            . 'past every documented endpoint.'
        );
    }

    /**
     * Probe the rendered Swagger UI page for the bundled chrome
     * that proves the UI mounted past the auth shell. Without
     * this, the no-console-errors gate above would only prove
     * the page didn't throw, not that the shell rendered.
     */
    private function assertSwaggerUiChromeRendered(Browser $browser): void
    {
        $source = (string) $browser->driver->getPageSource();

        $hasMount = str_contains($source, 'id="swagger-ui"');
        $hasUiAsset = str_contains($source, 'swagger-ui')
            && (str_contains($source, '.css') || str_contains($source, '.js'));

        $this->assertTrue(
            $hasMount,
            'Swagger UI page must render the `<div id="swagger-ui">` mount node — the '
            . 'bundled swagger-ui bootstrap binds to this exact selector. Without it, '
            . 'the JS bootstrap silently no-ops and operators see a blank shell.'
        );
        $this->assertTrue(
            $hasUiAsset,
            'Swagger UI page must reference the bundled swagger-ui CSS/JS asset — a '
            . 'regression that drops the asset link (broken `routes.assets_path` in '
            . 'l5-swagger config) would render an unstyled DOM with no interactivity.'
        );
    }
}
