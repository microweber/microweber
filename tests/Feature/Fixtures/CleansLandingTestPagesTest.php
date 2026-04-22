<?php

declare(strict_types=1);

namespace Tests\Feature\Fixtures;

use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Factories\LandingPageFactory;
use Tests\Browser\Support\LandingTestContentPurger;
use Tests\Browser\Traits\CleansLandingTestPages;
use Tests\TestCase;

/**
 * Coverage for the landing-test cleanup pipeline.
 *
 * The landing-page Dusk suite builds pages via
 * {@see LandingPageFactory} and expects every test to start with a
 * clean slate. If cleanup ever regresses, leftover content_data /
 * content_fields / revisions rows skew assertions in follow-up tests
 * and can mask real bugs — so the cascade is pinned down here at the
 * PHP layer where a failure is easy to read.
 *
 * We also assert the `#[After]` hook on {@see CleansLandingTestPages}
 * fires automatically, so test authors only need `use` the trait
 * and don't have to remember a tearDown call.
 */
final class CleansLandingTestPagesTest extends TestCase
{
    use CleansLandingTestPages;

    #[Test]
    public function purge_cascades_through_satellite_tables(): void
    {
        $landing = LandingPageFactory::make('cascade-target');

        // Seed a row in every satellite table so we can prove the
        // purge hits each of them.
        DB::table('content_data')->insert([
            'rel_type' => 'content',
            'rel_id' => $landing->pageId,
            'field_name' => 'test_marker',
            'field_value' => 'x',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('content_fields')->insert([
            'rel_type' => 'content',
            'rel_id' => $landing->pageId,
            'field' => 'live-edit-field',
            'value' => 'marker-body',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('content_revisions_history')->insert([
            'rel_type' => 'content',
            'rel_id' => $landing->pageId,
            'field' => 'live-edit-field',
            'value' => 'older',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Pre-conditions
        $this->assertSame(1, DB::table('content_data')
            ->where('rel_type', 'content')->where('rel_id', $landing->pageId)->count());
        $this->assertSame(1, DB::table('content_fields')
            ->where('rel_type', 'content')->where('rel_id', $landing->pageId)->count());
        $this->assertSame(1, DB::table('content_revisions_history')
            ->where('rel_type', 'content')->where('rel_id', $landing->pageId)->count());

        $purged = LandingTestContentPurger::purge($landing->pageId);

        $this->assertSame([$landing->pageId], $purged);
        $this->assertSame(0, DB::table('content')->where('id', $landing->pageId)->count(),
            'content row must be gone');
        $this->assertSame(0, DB::table('content_data')
            ->where('rel_type', 'content')->where('rel_id', $landing->pageId)->count(),
            'content_data satellite must be gone');
        $this->assertSame(0, DB::table('content_fields')
            ->where('rel_type', 'content')->where('rel_id', $landing->pageId)->count(),
            'content_fields satellite must be gone');
        $this->assertSame(0, DB::table('content_revisions_history')
            ->where('rel_type', 'content')->where('rel_id', $landing->pageId)->count(),
            'content_revisions_history satellite must be gone');
    }

    #[Test]
    public function purge_null_sweeps_every_landing_test_page(): void
    {
        $a = LandingPageFactory::make();
        $b = LandingPageFactory::make();

        $purged = LandingTestContentPurger::purge();

        $this->assertContains($a->pageId, $purged);
        $this->assertContains($b->pageId, $purged);
        $this->assertSame(
            0,
            DB::table('content')
                ->where('url', 'like', LandingPageFactory::SLUG_PREFIX . '%')
                ->count(),
            'no landing-test-* pages should remain'
        );
    }

    #[Test]
    public function purge_does_not_touch_unrelated_content_rows(): void
    {
        $landing = LandingPageFactory::make();

        // A non-landing page we must leave alone.
        $keeperId = save_content([
            'content_type' => 'page',
            'subtype' => 'static',
            'title' => 'Keeper — not a fixture',
            'url' => 'cleanup-keeper-' . uniqid(),
            'is_active' => 1,
        ]);
        $this->assertIsNumeric($keeperId);
        $keeperId = (int)$keeperId;

        try {
            LandingTestContentPurger::purge();

            $this->assertSame(0, DB::table('content')->where('id', $landing->pageId)->count(),
                'landing page must be gone');
            $this->assertSame(1, DB::table('content')->where('id', $keeperId)->count(),
                'unrelated page must NOT be touched');
        } finally {
            DB::table('content')->where('id', $keeperId)->delete();
        }
    }

    #[Test]
    public function cleanup_trait_exposes_the_laravel_wired_teardown_hook(): void
    {
        // The auto-cleanup contract is "use CleansLandingTestPages and
        // you're done" — Laravel's InteractsWithTestCaseLifecycle
        // auto-registers any `tearDown{TraitBasename}` method as a
        // `beforeApplicationDestroyed` callback. Pin the name so a
        // rename doesn't silently disable the cleanup.
        $this->assertTrue(
            method_exists($this, 'tearDownCleansLandingTestPages'),
            'CleansLandingTestPages must expose tearDownCleansLandingTestPages for Laravel to auto-wire'
        );
    }

    #[Test]
    public function laravel_hook_fires_before_application_destroyed(): void
    {
        // Seed a fixture, then invoke the hook directly. It must
        // succeed with the container alive — the original symptom of
        // using #[After] was "Target class [config] does not exist"
        // because the app had already been flushed.
        $landing = LandingPageFactory::make('teardown-hook');
        $this->assertSame(1, DB::table('content')->where('id', $landing->pageId)->count());

        $this->tearDownCleansLandingTestPages();

        $this->assertSame(0, DB::table('content')->where('id', $landing->pageId)->count(),
            'hook must purge the fixture while the container is still alive');
    }
}
