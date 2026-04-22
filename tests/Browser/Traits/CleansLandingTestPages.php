<?php

namespace Tests\Browser\Traits;

use Tests\Browser\Support\LandingTestContentPurger;

/**
 * Auto-cleanup for `landing-test-*` fixtures so reruns don't
 * accumulate orphan rows.
 *
 * Compose on top of any test class that builds landing pages via
 * {@see \Tests\Browser\Factories\LandingPageFactory} or
 * {@see LiveEditPageBuilderTrait}. After every test method, the
 * `tearDownCleansLandingTestPages` hook cascade-deletes every
 * landing-test-* content row plus its satellites (content_data,
 * content_fields, revisions, content_related, media, categories,
 * menus).
 *
 * Hook naming: Laravel's `InteractsWithTestCaseLifecycle` auto-
 * registers any `tearDown{TraitBasename}` method on a used trait as
 * a `beforeApplicationDestroyed` callback. That fires with the
 * container still alive (before `Application::flush()`), which
 * `#[After]` does not — so this trait intentionally uses the
 * Laravel-wired name rather than the PHPUnit attribute.
 *
 * All cascade logic lives in
 * {@see \Tests\Browser\Support\LandingTestContentPurger} so the
 * factory and this trait share a single source of truth.
 */
trait CleansLandingTestPages
{
    protected function tearDownCleansLandingTestPages(): void
    {
        LandingTestContentPurger::purge();
    }
}
