<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Factories\LandingPageFactory;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinBladeExists;
use Tests\Browser\Traits\AssertsSkinPublicSignatureRendered;
use Tests\Browser\Traits\AssertsSkinTagPersisted;
use Tests\Browser\Traits\CleansLandingTestPages;
use Tests\Browser\Traits\LiveEditPageBuilderTrait;
use Tests\DuskTestCase;

/**
 * Phase-2 landing-page coverage: Blog / skin-1 latest-posts teaser.
 *
 * The Bootstrap template's Blog skin renders a `<module type="posts"
 * template="skin-1">` inside a `.section`. The module resolves live
 * data from the DB — so the plan's "verify it lists real posts from
 * the DB" acceptance criterion requires:
 *
 *   1. Seed a landing page via {@see LandingPageFactory}.
 *   2. Seed a unique active post so we can pin the assertion to a
 *      row we just created instead of scanning the whole table.
 *   3. Open the landing page in live-edit, prime the layout handle,
 *      insert Blog / skin-1 via the picker, mark .changed + save.
 *   4. Assert the seeded post's title appears in:
 *        - the canvas DOM after the module has re-rendered; AND
 *        - the public page render.
 *      (The live edit canvas is where the site owner sees the post
 *      right after inserting; the public page is where real visitors
 *      see it.)
 *   5. Register the seeded post id on `landingCreatedContentIds` so
 *      the CleansLandingTestPages trait's cascade also purges it.
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
class LiveEditBlogSkin1Test extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinBladeExists;
    use AssertsSkinPublicSignatureRendered;
    use AssertsSkinTagPersisted;
    use CleansLandingTestPages;
    use LiveEditPageBuilderTrait;

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB
    }

    #[Test]
    public function blog_skin_1_lists_real_posts_from_db(): void
    {
        $this->assertSkinBladeExists('blog/skin-1');

        $landing = LandingPageFactory::make('Blog latest teaser');

        $suffix = uniqid();
        $postTitle = 'Landing Blog Post ' . $suffix;
        $postDescription = 'Seeded post body visible in the blog teaser ' . $suffix;

        $postId = $this->seedActivePost($postTitle, $postDescription);

        try {
            $this->browse(function (Browser $browser) use ($landing, $postTitle, $postDescription) {
                $this->loginAsAdmin($browser);
                $this->openInLiveEdit($browser, $landing->pageId);

                $this->primeLayoutHandleOnMainContent($browser);
                $this->insertLayoutByCategory($browser, 'Blog', 'blog/skin-1');

                $field = $this->waitForBlogSection($browser);
                $this->assertNotSame('', $field,
                    'Blog section should expose a field="layout-blog-skin-1-…" attribute after insertion');

                $this->markEditFieldsChanged($browser, $field);
                $this->saveLiveEdit($browser);

                $this->assertSkinTagPersisted($landing->pageId, 'blog/skin-1');

                $this->assertCanvasLoadedPosts($browser, $field, $postTitle);
                $this->assertPublicPageCarriesPost($browser, $landing->slug, $postTitle, $postDescription);

                // Public-render gate runs LAST: it re-navigates to the
                // public slug — already on it from the post-carries call,
                // but harmless — and any canvas-touching call after this
                // would crash since mw.app.canvas is admin-only.
                $this->assertSkinPublicSignatureRendered(
                    $browser,
                    $landing->slug,
                    ['field="layout-blog-skin-1-'],
                );
            });
        } finally {
            // Remove the seed post even if an assertion above failed;
            // the landing page itself is purged by the trait.
            DB::table('content')->where('id', $postId)->delete();
            DB::table('content_data')
                ->where('rel_type', 'content')
                ->where('rel_id', $postId)
                ->delete();
            DB::table('content_fields')
                ->where('rel_type', 'content')
                ->where('rel_id', $postId)
                ->delete();
        }
    }

    /**
     * Create an active, published post via `save_content()` so the
     * posts module will pick it up. Returns the new content id.
     */
    private function seedActivePost(string $title, string $description): int
    {
        $id = save_content([
            'content_type' => 'post',
            'subtype' => 'post',
            'title' => $title,
            'url' => 'landing-test-post-' . substr(md5($title . microtime(true)), 0, 10),
            'description' => $description,
            'content' => '<p>' . $description . '</p>',
            'is_active' => 1,
            'is_deleted' => 0,
        ]);

        if (!$id || !is_numeric($id)) {
            throw new \RuntimeException(
                'seedActivePost: save_content returned a non-id value: ' . var_export($id, true)
            );
        }

        return (int)$id;
    }

    /**
     * Set mw.app.liveEdit.handles.get('layout') target to the page's
     * `.section.edit.main-content` so the Insert Layout picker's
     * fallback target resolves.
     */
    private function primeLayoutHandleOnMainContent(Browser $browser): void
    {
        $primed = $browser->script("
            if (!(window.mw && mw.app && mw.app.canvas
                && typeof mw.app.canvas.getDocument === 'function')) {
                return 'NO_CANVAS';
            }
            var doc = mw.app.canvas.getDocument();
            var target = doc.querySelector('.section.edit.main-content')
                || doc.querySelector('.section.edit[field=\"content\"]')
                || doc.querySelector('[data-layout-container]');
            if (!target) return 'NO_MAIN_CONTENT';
            if (mw.app.liveEdit && mw.app.liveEdit.handles) {
                var h = mw.app.liveEdit.handles.get('layout');
                if (h && typeof h.set === 'function') {
                    h.set(target);
                }
            }
            return 'OK';
        ");

        $this->assertSame('OK', $primed[0] ?? 'UNKNOWN',
            'Layout handle must resolve to the clean-layout main-content section');
    }

    private function waitForBlogSection(Browser $browser): string
    {
        for ($i = 0; $i < 30; $i++) {
            $res = $browser->script("
                var doc = mw.app.canvas.getDocument();
                var sec = doc.querySelector('section.section.edit[field^=\"layout-blog-skin-1-\"]');
                if (!sec) return '';
                return sec.getAttribute('field') || '';
            ");
            $fieldAttr = (string)($res[0] ?? '');
            if ($fieldAttr !== '') {
                $browser->pause(700);
                return $fieldAttr;
            }
            $browser->pause(500);
        }
        $browser->screenshot('fail-blog-section-missing');
        throw new \RuntimeException('Blog section never appeared in the canvas within 15s');
    }

    /**
     * Tag the blog section and its main-content parent with
     * `.changed` — the save pipeline's collectData() only picks up
     * `.edit.changed` nodes.
     */
    private function markEditFieldsChanged(Browser $browser, string $field): void
    {
        $res = $browser->script("
            var doc = mw.app.canvas.getDocument();
            var sec = doc.querySelector('section[field=' + JSON.stringify(" . json_encode($field) . ") + ']');
            if (!sec) return 'NO_SEC';
            sec.classList.add('changed');
            var p = sec.parentElement;
            while (p) {
                if (p.classList && p.classList.contains('edit')) {
                    p.classList.add('changed');
                    break;
                }
                p = p.parentElement;
            }
            return 'OK';
        ");
        $this->assertSame('OK', $res[0] ?? 'UNKNOWN', 'Must be able to tag .edit.changed for save');
    }

    /**
     * Wait for the posts module to re-render (it loads via AJAX after
     * `mw.load_module`) and carry our seeded post title.
     */
    private function assertCanvasLoadedPosts(
        Browser $browser,
        string $field,
        string $expectedTitle
    ): void {
        for ($i = 0; $i < 20; $i++) {
            $state = $browser->script("
                var doc = mw.app.canvas.getDocument();
                var sec = doc.querySelector('section[field=' + JSON.stringify(" . json_encode($field) . ") + ']');
                if (!sec) return { present: false };
                var html = sec.innerHTML || '';
                return {
                    present: true,
                    hasTitle: html.indexOf(" . json_encode($expectedTitle) . ") !== -1,
                    hasPostHolder: !!sec.querySelector('.post-holder, .mw-products-title, [itemtype*=\"BlogPosting\"]')
                };
            ");
            $state = $state[0] ?? ['present' => false];
            if (!empty($state['hasTitle'])) {
                return;
            }
            $browser->pause(500);
        }

        $browser->screenshot('fail-blog-canvas-missing-post');
        $this->fail('Canvas posts module never rendered the seeded post title within 10s');
    }

    /**
     * Visit the public page and assert both the seeded post title and
     * its description are in the rendered HTML. This is the authentic
     * "site visitor view" check.
     */
    private function assertPublicPageCarriesPost(
        Browser $browser,
        string $slug,
        string $expectedTitle,
        string $expectedDescription
    ): void {
        $browser->visit('/' . ltrim($slug, '/'))->pause(3000);
        $source = $browser->driver->getPageSource();

        $this->assertStringContainsString(
            $expectedTitle,
            $source,
            "Public /{$slug} must render the seeded post title in the blog teaser"
        );
        // Description is Str::limit'd to 150 chars but our suffix is
        // at the start — so the full string should survive the trim.
        $this->assertStringContainsString(
            $expectedDescription,
            $source,
            "Public /{$slug} must render the seeded post description in the blog teaser"
        );
    }
}
