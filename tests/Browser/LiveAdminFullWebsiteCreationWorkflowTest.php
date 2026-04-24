<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Support\WorkflowFixturePurger;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\CleansWorkflowFixtures;
use Tests\DuskTestCase;

/**
 * Plan A — Full website-creation Dusk workflow (scaffold).
 *
 * One end-to-end Dusk test that walks a first-time operator from a
 * fresh install to a published, publicly-rendered site. The
 * individual stages live in TODO.md under Plan A.3 and get filled
 * in as follow-up methods on this class.
 *
 * Acceptance criteria this class satisfies (TODO Plan A.1):
 *
 *   1. **Exists at the right path** — by being this file.
 *   2. **Part of the default `php artisan dusk` run** — no Group
 *      attribute, no skip, no opt-in marker.
 *   3. **≤15 minutes end-to-end** — foundation completes in
 *      seconds; each stage added later has its own time budget.
 *   4. **Seeds and purges its own fixture** — the
 *      {@see CleansWorkflowFixtures} trait asserts zero residue on
 *      `content`, `content_data`, `media`, `options`, `users`, and
 *      `menus` after every test method.
 *
 * Fixture contract:
 *   Every row this test creates MUST carry a
 *   `WorkflowFixturePurger::FIXTURE_MARKER` marker — slug prefix on
 *   `content.url`, the marker substring in `content.title` or
 *   `media.filename`, the option-key prefix on `options`, or the
 *   `@workflow-fixture.test` domain on `users`. The purger
 *   cross-checks the snapshot before and after every method; a
 *   stage that creates a row without a marker FAILS the test.
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
class LiveAdminFullWebsiteCreationWorkflowTest extends DuskTestCase
{
    use AdminLoginTrait;
    use CleansWorkflowFixtures;

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

    #[Test]
    public function fixture_harness_seeds_and_purges_its_own_content_row(): void
    {
        // Prove the purger actually reaches fixture rows: create a
        // content row with the workflow-fixture marker INSIDE the
        // test. The CleansWorkflowFixtures tearDown will purge it,
        // then assert the snapshot counts match — so a regression
        // in the purger surfaces as a tearDown failure on this
        // method specifically, not as a silent leak into the next
        // test method's baseline.
        $marker = WorkflowFixturePurger::FIXTURE_MARKER . '-harness-proof';

        $contentId = DB::table('content')->insertGetId([
            'title' => 'Workflow harness proof — ' . WorkflowFixturePurger::FIXTURE_MARKER,
            'content_type' => 'page',
            'subtype' => 'static',
            'url' => $marker,
            'is_active' => 1,
            'is_home' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('content_data')->insert([
            'rel_type' => 'content',
            'rel_id' => $contentId,
            'field_name' => WorkflowFixturePurger::FIXTURE_OPTION_KEY_PREFIX . 'probe',
            'field_value' => $marker,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Sanity: the rows are actually there before tearDown runs.
        $this->assertSame(1, DB::table('content')->where('id', $contentId)->count(),
            'Seed row must land in content');
        $this->assertSame(1,
            DB::table('content_data')->where('rel_id', $contentId)->count(),
            'Seed row must land in content_data');

        // tearDownCleansWorkflowFixtures will purge, then assert
        // zero residue. No further asserts needed here.
    }

    // Plan A.3 stage methods — stubbed out as follow-up tasks in TODO.md.
    //
    // Each stage MUST:
    //   - create only rows carrying a workflow-fixture marker so the
    //     tearDown purger can reach them
    //   - assert its primary DB-level invariant (source of truth)
    //   - assert at least one rendered-DOM marker (operator-visible)
    //
    // Add them one per commit — the foundation + fixture harness
    // above are enough to satisfy the first two Plan A.1 acceptance
    // bullets while the stage methods are authored.
}
