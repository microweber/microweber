<?php

declare(strict_types=1);

namespace Tests\Feature\Fixtures;

use Illuminate\Support\Facades\DB;
use MicroweberPackages\User\Models\User;
use Modules\Page\Models\Page;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Factories\LandingPageFactory;
use Tests\Browser\Traits\CleansLandingTestPages;
use Tests\TestCase;

/**
 * Coverage for {@see \Tests\Browser\Factories\LandingPageFactory}.
 *
 * The factory seeds three fixtures that every live-edit landing-page
 * Dusk test depends on — admin user, Bootstrap current_template, and a
 * clean "landing-test-*" page. A broken factory would surface as
 * flaky Dusk runs with opaque failures (blank iframes, 404 slugs,
 * login loops), so we assert each guarantee explicitly at the PHP
 * layer where the failure is easy to read.
 */
final class LandingPageFactoryTest extends TestCase
{
    use CleansLandingTestPages;

    #[Test]
    public function make_returns_a_page_with_bootstrap_template_and_slug_prefix(): void
    {
        $landing = LandingPageFactory::make('Factory smoke');

        $this->assertGreaterThan(0, $landing->pageId);
        $this->assertStringStartsWith(LandingPageFactory::SLUG_PREFIX, $landing->slug);
        $this->assertNotSame('', $landing->link);

        $page = Page::find($landing->pageId);
        $this->assertNotNull($page);
        $this->assertSame('Bootstrap', $page->active_site_template);
        $this->assertSame('Factory smoke', $page->title);
        $this->assertSame($landing->slug, $page->url);
        $this->assertSame(1, (int)$page->is_active);
    }

    #[Test]
    public function make_defaults_title_when_not_provided(): void
    {
        $landing = LandingPageFactory::make();

        $page = Page::find($landing->pageId);
        $this->assertNotNull($page);
        $this->assertStringStartsWith('Landing smoke ', (string)$page->title);
    }

    #[Test]
    public function make_ensures_current_template_is_bootstrap(): void
    {
        DB::table('options')->updateOrInsert(
            ['option_key' => 'current_template', 'option_group' => 'template'],
            ['option_value' => 'big2', 'updated_at' => now(), 'created_at' => now()]
        );

        LandingPageFactory::make();

        $row = DB::table('options')
            ->where('option_key', 'current_template')
            ->where('option_group', 'template')
            ->first();

        $this->assertNotNull($row);
        $this->assertSame('Bootstrap', $row->option_value);
    }

    #[Test]
    public function make_ensures_admin_user_exists_with_is_admin_flag(): void
    {
        LandingPageFactory::make();

        $admin = User::where('email', LandingPageFactory::ADMIN_EMAIL)->first();
        $this->assertNotNull($admin, 'Admin user should exist after factory make()');
        $this->assertSame(1, (int)$admin->is_admin);
        $this->assertSame(1, (int)$admin->is_active);
    }

    #[Test]
    public function make_repairs_admin_flag_when_downgraded(): void
    {
        $admin = User::where('email', LandingPageFactory::ADMIN_EMAIL)->first();
        $this->assertNotNull($admin, 'Pre-condition: admin row should exist in the test DB');

        User::where('id', $admin->id)->update(['is_admin' => 0]);
        $this->assertSame(0, (int)User::find($admin->id)->is_admin);

        LandingPageFactory::make();

        $this->assertSame(1, (int)User::find($admin->id)->is_admin,
            'Factory should repair is_admin back to 1');
    }

    #[Test]
    public function cleanup_removes_only_the_factory_instance_page(): void
    {
        $a = LandingPageFactory::make('cleanup-A');
        $b = LandingPageFactory::make('cleanup-B');

        $a->cleanup();

        $this->assertNull(Page::find($a->pageId),
            'cleanup() should delete the factory instance page');
        $this->assertNotNull(Page::find($b->pageId),
            'cleanup() must not touch unrelated landing-test pages');
    }

    #[Test]
    public function cleanup_all_removes_every_landing_test_page(): void
    {
        $a = LandingPageFactory::make();
        $b = LandingPageFactory::make();

        LandingPageFactory::cleanupAll();

        $this->assertNull(Page::find($a->pageId));
        $this->assertNull(Page::find($b->pageId));
        $this->assertSame(
            0,
            Page::where('url', 'like', LandingPageFactory::SLUG_PREFIX . '%')->count(),
            'cleanupAll should leave no landing-test-* rows behind'
        );
    }
}
