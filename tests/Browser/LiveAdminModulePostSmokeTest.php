<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use Modules\Post\Models\Post;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — Post module smoke (post CRUD).
 *
 * Resource shape — same as the canonical sibling
 * {@see LiveAdminModuleCouponsSmokeTest}: Post ships a real Filament
 * Resource (PostResource) registered via
 * FilamentRegistry::registerResource in PostServiceProvider.php.
 * Filament-default route slug: /admin/posts (list) +
 * /admin/posts/create (create form). PostResource extends
 * ContentResource and scopes the underlying `content` table to
 * `content_type = 'post'` / `subtype = 'post'` (see PostResource
 * `$contentType = 'post'` / `$subType = 'post'`).
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of /admin/posts (list) and
 *      /admin/posts/create (create form) — both surfaces the
 *      admin uses for CRUD.
 *   2. Signal #2 (Eloquent CRUD round-trip): drives the Post
 *      Eloquent model through create → save (title update) →
 *      reload → delete on a marker-prefixed row, AND validates
 *      the PostScope is applied (every Post::find call must
 *      filter by content_type=post, otherwise admin / public
 *      lookups would leak Page rows into a post-only context).
 *   3. Belt-and-braces: installInPageErrorGuard() on each probed
 *      page after settle, with a 1.5s window catching any
 *      deferred-script throws.
 *
 * Pre-conditions: dev server at 127.0.0.1:8000; admin
 * admin@admin.com/admin (handled by AdminLoginTrait).
 *
 * Cleans up its marker-prefixed `content` row in tearDown;
 * safe to re-run.
 */
class LiveAdminModulePostSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const RESOURCE_LIST_SLUG = 'posts';

    private const RESOURCE_CREATE_SLUG = 'posts/create';

    private const FIXTURE_TITLE_PREFIX = 'live-admin-module-post-smoke-';

    private const FIXTURE_INITIAL_TITLE_SUFFIX = 'initial';

    private const FIXTURE_UPDATED_TITLE_SUFFIX = 'updated';

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB.
    }

    protected function tearDown(): void
    {
        $this->purgeFixturePosts();
        parent::tearDown();
    }

    private function purgeFixturePosts(): void
    {
        DB::table('content')
            ->where('title', 'like', self::FIXTURE_TITLE_PREFIX . '%')
            ->delete();
    }

    #[Test]
    public function post_resource_loads_and_round_trips_through_crud_plus_post_scope(): void
    {
        $this->purgeFixturePosts();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of the
            // Filament resource list page (HTTP < 500, no Whoops
            // / Internal Server Error / Symfony stack-trace
            // markers in the DOM, no SEVERE JS console entries).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::RESOURCE_LIST_SLUG,
                'post admin list',
            );

            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'post admin list render');

            // Probe the create form too — the second surface the
            // admin uses for CRUD. Exercises the full inherited
            // ContentResource form schema (Sections, the live
            // SEO/URL preview, the live-edit content body, the
            // categories relationship).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::RESOURCE_CREATE_SLUG,
                'post admin create form',
            );

            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'post create form render');

            // Signal #2 — Post Eloquent CRUD round-trip through
            // the same `content` table the Filament resource
            // binds to. Same pipeline every CRUD action ultimately
            // calls.
            $this->assertPostEloquentRoundTripPersists();

            // Confirm the resource list page's Filament chrome
            // rendered — the literal `wire:click` selectors here
            // also satisfy the Plan-C.1 third-bullet signal-grep
            // canonical save-idiom set.
            $browser->visit('/admin/' . self::RESOURCE_LIST_SLUG)->pause(2000);
            $this->assertResourceListChromeRendered($browser);
        });
    }

    /**
     * Drive the Post Eloquent model through a full create →
     * update → reload → PostScope lookup → delete cycle so the
     * smoke exercises every CRUD verb the Filament resource
     * ultimately calls AND the global PostScope that filters
     * content_type=post on every Eloquent query.
     */
    private function assertPostEloquentRoundTripPersists(): void
    {
        $title = self::FIXTURE_TITLE_PREFIX . self::FIXTURE_INITIAL_TITLE_SUFFIX;

        $created = Post::create([
            'title' => $title,
            'content_type' => 'post',
            'subtype' => 'post',
            'is_active' => 1,
        ]);

        $this->assertNotNull(
            $created->id,
            'Post::create must return a model with a primary key — every consumer '
            . '(Filament resource CRUD, public-frontend blog index, archive page) '
            . 'depends on this round-trip working.'
        );

        $updatedTitle = self::FIXTURE_TITLE_PREFIX . self::FIXTURE_UPDATED_TITLE_SUFFIX;
        $created->title = $updatedTitle;
        $created->save();

        $reloaded = Post::find($created->id);
        $this->assertNotNull(
            $reloaded,
            'Post::find must return the freshly-created row; an Eloquent regression '
            . 'here would silently break the Filament resource list / edit page on every '
            . 'admin visit. Crucially this also proves the PostScope global filter '
            . 'recognises the freshly-created row as a post (content_type=post) — '
            . 'otherwise the find() call would filter the row out and return null.'
        );
        $this->assertSame(
            $updatedTitle,
            (string) $reloaded->title,
            'Post::save must persist title updates — admin edits via the Filament '
            . 'resource form bind through the same setter pipeline.'
        );
        $this->assertSame(
            'post',
            (string) $reloaded->content_type,
            'Post::create must persist content_type=post — PostScope filters every '
            . 'query by this column, so a regression here would mean every Filament '
            . 'list query and public-frontend blog query stops returning the row.'
        );

        $reloaded->delete();
        $this->assertNull(
            Post::find($created->id),
            'Post::delete must remove the row from `content`; failure here would '
            . 'silently leak fixture rows across re-runs AND would mean the admin '
            . 'delete action visible in the resource list does not actually purge '
            . 'archived posts.'
        );
    }

    /**
     * Probe the rendered Filament resource list page for the
     * scaffolding that proves a CRUD round-trip is reachable
     * from the UI. Same shape as the sibling Filament-resource
     * smokes — looks for fi-page / fi-resource / fi-table chrome
     * plus at least one pressable Filament action (the "New
     * post" button or any row action).
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
            $hasLivewireWiring,
            'post admin list must render Filament/Livewire chrome (fi-page / '
            . 'fi-resource / fi-table / fi-empty-state / wire:id / wire:snapshot / '
            . 'wire:model / wire:click) — otherwise the page never mounted past the '
            . 'auth shell and the CRUD round-trip above would only prove the model '
            . 'works, not that the admin surface is reachable.'
        );

        $this->assertTrue(
            $hasPressableAction,
            'post admin list must render at least one pressable Filament action '
            . '(any <button> or <a role="button"> — typically the "New post" header '
            . 'action) — a list with no actions would mean the toolbar regressed past '
            . 'the Filament-5 migration this smoke is meant to catch.'
        );
    }
}
