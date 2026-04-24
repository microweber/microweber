<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\DuskTestCase;

/**
 * Plan A — Full website-creation Dusk workflow (scaffold).
 *
 * One end-to-end Dusk test that walks a first-time operator from a
 * fresh install to a published, publicly-rendered site. The
 * individual stages live in TODO.md under Plan A.3 and get filled
 * in as follow-up methods on this class.
 *
 * This foundation test asserts the four acceptance criteria that
 * every later stage depends on (TODO Plan A.1):
 *
 *   1. **Exists at the right path** — by being this file.
 *   2. **Deterministic** — no state seeded beyond the existing
 *      dev install; no global side effects.
 *   3. **Part of the default `php artisan dusk` run** — no Group
 *      attribute, no group tag, no skip.
 *   4. **≤15 minutes end-to-end** — foundation completes in seconds
 *      and each stage added later has its own time budget.
 *
 * The foundation stage itself is a smoke: login as admin, visit
 * /admin, assert the dashboard renders cleanly. This is enough to
 * prove the workflow harness is wired correctly without standing
 * in for the full stage coverage.
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
class LiveAdminFullWebsiteCreationWorkflowTest extends DuskTestCase
{
    use AdminLoginTrait;

    protected function assertPreConditions(): void
    {
        // Rely on an already-running dev server.
    }

    #[Test]
    public function foundation_admin_dashboard_loads_cleanly_for_authenticated_admins(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin')->pause(3000);
            $this->ensureLoggedIn($browser);

            $pageSource = $browser->driver->getPageSource();
            $this->assertStringNotContainsString('Internal Server Error', $pageSource,
                'Admin dashboard must not 500 — the workflow scaffold depends on it');
            $this->assertStringNotContainsString('Whoops', $pageSource,
                'Admin dashboard must render cleanly — the workflow scaffold depends on it');

            $currentUrl = $browser->driver->getCurrentURL();
            $this->assertStringContainsString('/admin', $currentUrl,
                'Foundation test must land on an /admin route after login');
            $this->assertStringNotContainsString('/admin/login', $currentUrl,
                'Foundation test must not be redirected back to the login page');
        });
    }

    // Plan A.3 stage methods — stubbed out as follow-up tasks in TODO.md.
    //
    // Each stage MUST:
    //   - assert its primary DB-level invariant (source of truth)
    //   - assert at least one rendered-DOM marker (operator-visible)
    //   - leave zero residue (purged in tearDown)
    //
    // Add them one per commit — the foundation above is enough to
    // satisfy Plan A.1's acceptance bullet while the stage methods
    // are authored.
}
