<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Hash;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use MicroweberPackages\User\Models\User;
use Modules\Content\Models\Content;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Support\LandingTestContentPurger;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\LiveEditPageBuilderTrait;
use Tests\DuskTestCase;

/**
 * Full user-flow Dusk test on the Big2 template — task-2026-04-29-90fb13.
 *
 * "Think as a user" means simulating what a content editor actually
 * does in one sitting on a fresh Big2-template page:
 *
 *   1. Open a Big2 page in live-edit (the page is pre-populated with
 *      a single editable section + a posts-list module — same shape
 *      a user gets after clicking Insert Layout once).
 *   2. Inline-edit the heading and paragraph text inside the canvas
 *      iframe (the way the inline editor handles real keyboard input).
 *   3. Click SAVE; reload the live-edit view; assert the edited text
 *      survived the round trip — proves the live-edit save pipeline
 *      persisted the iframe DOM mutations to disk.
 *   4. Open Add-Post via the live-edit toolbar action and create a
 *      post with a unique title.
 *   5. Visit the public Big2 page URL and assert (a) the edited
 *      heading text appears, (b) the edited paragraph appears, and
 *      (c) the newly-created post title is rendered inside the
 *      embedded posts-list module — the `data-limit="1000"`
 *      attribute on the shortcode means pagination cannot hide it.
 *
 * This is the broadest end-to-end Dusk test in the repo for Big2.
 * Previous Big2 tests (LiveEditAddContentBig2Test) covered just the
 * Add-action surface; previous post-module tests covered just the
 * inline edit/create. This test exercises **the whole chain**:
 * inline edit + save → reload → toolbar add → public render.
 *
 * Cleanup: only purges the `bigflow-` slugs the test created.
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin;
 * Big2 template installed.
 */
class Big2UserFlowFullPageTest extends DuskTestCase
{
    use AdminLoginTrait;
    use LiveEditPageBuilderTrait;

    private const ADMIN_EMAIL = 'admin@admin.com';
    private const ADMIN_PASSWORD = 'admin';
    private const SLUG_PREFIX = 'bigflow-';

    /** @var int[] */
    private array $createdIds = [];

    #[Test]
    public function full_user_flow_on_big2_inline_edit_save_add_post_then_public_render(): void
    {
        $this->ensureAdminUser();
        $this->ensureBig2Active();

        $smokeRunSlug = substr(md5(microtime(true) . Str::random(6)), 0, 10);

        $editedHeading = 'Big2 flow heading ' . $smokeRunSlug;
        $editedParagraph = 'Big2 flow paragraph body ' . $smokeRunSlug;
        $createdPostTitle = 'Big2 flow post ' . $smokeRunSlug;

        $pageId = $this->createBig2HostPage($smokeRunSlug);

        $this->browse(function (Browser $browser) use (
            $pageId,
            $editedHeading,
            $editedParagraph,
            $createdPostTitle
        ) {
            $this->loginAsAdmin($browser);

            $pageLink = (string)content_link($pageId);
            $this->assertNotSame('', $pageLink, 'content_link returned empty for Big2 host page');

            // ---- Step 1: open in live-edit ----
            $browser->visit('/admin/live-edit?url=' . urlencode($pageLink))
                ->pause(5000);
            $browser->waitFor('iframe', 20)->pause(3000);

            // ---- Step 2: inline-edit heading + paragraph in canvas ----
            // The page content seeded by createBig2HostPage() has
            // .bigflow-edit-heading and .bigflow-edit-paragraph
            // marker classes inside an `.edit` section, so the
            // editInlineText helper can target them deterministically.
            $this->editInlineText($browser, '.bigflow-edit-heading', $editedHeading);
            $this->editInlineText($browser, '.bigflow-edit-paragraph', $editedParagraph);

            // ---- Step 3: save + reload → assert round-trip ----
            $this->saveLiveEdit($browser);
            $browser->pause(2000);

            $browser->visit('/admin/live-edit?url=' . urlencode($pageLink))
                ->pause(5000);
            $browser->waitFor('iframe', 20)->pause(2500);

            $survivedRoundTrip = $browser->script(
                "
                if (!(window.mw && mw.app && mw.app.canvas
                    && typeof mw.app.canvas.getDocument === 'function')) return 'NO_CANVAS';
                var doc = null;
                try { doc = mw.app.canvas.getDocument(); } catch (e) { doc = null; }
                if (!doc) {
                    var iframes = document.querySelectorAll('iframe');
                    if (iframes.length) doc = iframes[0].contentDocument;
                }
                if (!doc) return 'NO_DOC';
                var h = doc.querySelector('.bigflow-edit-heading');
                var p = doc.querySelector('.bigflow-edit-paragraph');
                return JSON.stringify({
                    h: h ? (h.textContent || '').trim() : null,
                    p: p ? (p.textContent || '').trim() : null
                });
            "
            );
            $payload = json_decode((string)($survivedRoundTrip[0] ?? '{}'), true) ?: [];
            $this->assertSame(
                $editedHeading,
                $payload['h'] ?? null,
                'Heading edit did not survive live-edit save + reload round trip on Big2.'
            );
            $this->assertSame(
                $editedParagraph,
                $payload['p'] ?? null,
                'Paragraph edit did not survive live-edit save + reload round trip on Big2.'
            );

            // ---- Step 4: add a post via the toolbar addPostAction ----
            $mountResult = $browser->script(
                "
                return (async function () {
                    try {
                        var root = document.querySelector('[wire\\\\:id]');
                        if (!root) return 'NO_WIRE_ROOT';
                        var wire = window.Livewire.find(root.getAttribute('wire:id'));
                        if (!wire) return 'NO_WIRE';
                        await wire.mountAction('addPostAction', {});
                        return 'OK';
                    } catch (e) { return 'EXC:' + (e && e.message ? e.message : e); }
                })();
            "
            );
            $browser->pause(2500);
            $this->assertSame(
                'OK',
                (string)($mountResult[0] ?? ''),
                'mountAction(addPostAction) failed on Big2'
            );

            $browser->waitUsing(15, 200, function () use ($browser) {
                $found = $browser->script(
                    "
                    return Array.from(document.querySelectorAll('form'))
                        .some(f => f.getAttribute('wire:submit.prevent') === 'callMountedAction'
                                || f.getAttribute('wire:submit') === 'callMountedAction')
                        ? 1 : 0;
                "
                );
                return ($found[0] ?? 0) === 1;
            });

            $filled = $browser->script(
                "
                var title = " . json_encode($createdPostTitle) . ";
                var form = Array.from(document.querySelectorAll('form'))
                    .find(f => f.getAttribute('wire:submit.prevent') === 'callMountedAction'
                            || f.getAttribute('wire:submit') === 'callMountedAction');
                if (!form) return 'NO_FORM';

                var setVal = function (input, value) {
                    if (!input) return false;
                    input.focus();
                    input.value = value;
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                    return true;
                };

                var titleInput = null;
                var fields = form.querySelectorAll('input, textarea');
                for (var i = 0; i < fields.length; i++) {
                    var el = fields[i];
                    var attrs = el.attributes;
                    for (var j = 0; j < attrs.length; j++) {
                        var a = attrs[j];
                        if (!a.name.startsWith('wire:model')) continue;
                        if (!titleInput && /(^|\\.)title\$/.test(a.value || '')) titleInput = el;
                    }
                }
                if (!titleInput) {
                    var visible = Array.from(form.querySelectorAll('input[type=\"text\"], input:not([type])'))
                        .filter(el => !el.disabled && !el.readOnly && el.getBoundingClientRect().width > 0);
                    if (visible.length > 0) titleInput = visible[0];
                }
                if (!setVal(titleInput, title)) return 'NO_TITLE_INPUT';
                return 'OK';
            "
            );
            $this->assertSame('OK', (string)($filled[0] ?? ''), 'Could not fill post title');
            $browser->pause(900);

            // Set is_active=true so the post is published (defaults to false per AI-778).
            $browser->script(
                "
                document.querySelectorAll('[wire\\\\:id]').forEach(function (el) {
                    try {
                        var wire = window.Livewire.find(el.getAttribute('wire:id'));
                        if (!wire) return;
                        var ma = wire.get ? wire.get('mountedActions') : null;
                        if (ma && Array.isArray(ma) && ma.length > 0) {
                            wire.\$set('mountedActions.0.data.is_active', true);
                        }
                    } catch (e) {}
                });
                "
            );
            $browser->pause(500);

            // Use wire.callMountedAction() directly — canonical approach that
            // bypasses the #save-button confirm dialog and canvas-drag save path.
            $browser->script("
                (async function () {
                    var root = document.querySelector('[wire\\\\:id]');
                    if (!root) return;
                    var wire = window.Livewire.find(root.getAttribute('wire:id'));
                    if (!wire) return;
                    await wire.callMountedAction();
                })();
            ");
            $browser->pause(500); // pause replaced the old #save-button click

            // Poll for the new post row.
            $deadline = microtime(true) + 15.0;
            $foundPost = false;
            do {
                $row = Content::where('title', $createdPostTitle)
                    ->where('content_type', 'post')
                    ->first();
                if ($row) { $foundPost = true; break; }
                usleep(500_000);
            } while (microtime(true) < $deadline);

            $this->assertTrue(
                $foundPost,
                "Post '{$createdPostTitle}' did not persist within 15s after live-edit SAVE on Big2."
            );
        });

        // Track the post for cleanup.
        $row = Content::where('title', $createdPostTitle)
            ->where('content_type', 'post')
            ->first();
        if ($row) {
            $this->createdIds[] = (int)$row->id;
        }

        // ---- Step 5: assert public Big2 page renders all 3 edits ----
        $publicLink = (string)content_link($pageId);
        $this->browse(function (Browser $browser) use (
            $publicLink,
            $editedHeading,
            $editedParagraph,
            $createdPostTitle
        ) {
            $browser->visit($publicLink)->pause(2500);
            $body = (string)($browser->script("return document.body.innerHTML;")[0] ?? '');

            $this->assertStringContainsString(
                $editedHeading,
                $body,
                'Edited heading missing from public Big2 page render.'
            );
            $this->assertStringContainsString(
                $editedParagraph,
                $body,
                'Edited paragraph missing from public Big2 page render.'
            );
            $this->assertStringContainsString(
                $createdPostTitle,
                $body,
                'Newly-created post title missing from public Big2 posts-list render. '
                . 'data-limit=1000 should make it visible regardless of pagination.'
            );
        });
    }

    /**
     * Seed a Big2 page that already contains:
     *   - a `.edit` section with a heading + paragraph carrying
     *     marker classes (.bigflow-edit-heading / -paragraph) so
     *     the test can target them via the canvas iframe;
     *   - a `<module type="posts" data-limit="1000" />` shortcode so
     *     the new post added in step 4 shows up on the public render.
     *
     * Putting these markers directly in `content` instead of going
     * through the Insert Layout UI keeps the test focused on the
     * inline-edit + save + add-post flow rather than the layout
     * picker (which has its own dedicated tests).
     */
    private function createBig2HostPage(string $slug): int
    {
        $pageSlug = self::SLUG_PREFIX . 'host-' . $slug;
        $title = 'Big2 user-flow ' . $slug;
        $moduleId = 'modulemw-bigflow-' . $slug;

        // The .edit class + a stable field= attribute make the
        // outer section editable by the live-edit canvas. Inside, an
        // <h3> and <p> with marker classes give the test
        // deterministic selectors. The container/section structure
        // mirrors what Big2's Content/skin-1 layout produces.
        $content = '<section class="section">'
            . '<div class="container mw-layout-container safe-mode no-element text-center edit" '
            . 'field="layout-bigflow-' . htmlspecialchars($slug, ENT_QUOTES) . '" rel="module">'
            . '<div class="row text-center safe-mode">'
            . '<div class="col-12 col-lg-8 mx-auto regular-mode allow-drop allow-select">'
            . '<h3 class="bigflow-edit-heading" data-mwplaceholder="Enter title here">Big2 default heading text</h3>'
            . '<p class="bigflow-edit-paragraph" data-mwplaceholder="Enter text here">Big2 default paragraph text.</p>'
            . '</div>'
            . '</div>'
            . '</div>'
            . '</section>'
            . '<section class="container py-5">'
            . '<module type="posts" id="' . htmlspecialchars($moduleId, ENT_QUOTES) . '" '
            . 'data-limit="1000" template="default" />'
            . '</section>';

        $id = save_content([
            'content_type' => 'page',
            'subtype' => 'static',
            'title' => $title,
            'url' => $pageSlug,
            'active_site_template' => 'Big2',
            'is_active' => 1,
            'content' => $content,
        ]);

        if (!$id || !is_numeric($id)) {
            throw new \RuntimeException(
                'createBig2HostPage: save_content returned a non-id value: '
                . var_export($id, true)
            );
        }

        $this->createdIds[] = (int)$id;
        return (int)$id;
    }

    private function ensureAdminUser(): void
    {
        $user = User::where('email', self::ADMIN_EMAIL)->first();
        if (!$user) {
            $user = new User();
            $user->email = self::ADMIN_EMAIL;
            $user->username = 'admin';
            $user->password = Hash::make(self::ADMIN_PASSWORD);
            $user->is_admin = 1;
            $user->is_active = 1;
            $user->is_verified = 1;
            $user->first_name = 'Admin';
            $user->last_name = 'User';
            $user->save();
            return;
        }
        $dirty = false;
        if ((int)$user->is_admin !== 1) { $user->is_admin = 1; $dirty = true; }
        if ((int)$user->is_active !== 1) { $user->is_active = 1; $dirty = true; }
        if ($dirty) { $user->save(); }
    }

    private function ensureBig2Active(): void
    {
        $row = DB::table('options')
            ->where('option_key', 'current_template')
            ->where('option_group', 'template')
            ->first();

        if ($row) {
            if ($row->option_value !== 'Big2') {
                DB::table('options')
                    ->where('id', $row->id)
                    ->update(['option_value' => 'Big2', 'updated_at' => now()]);
            }
            return;
        }

        DB::table('options')->insert([
            'option_key' => 'current_template',
            'option_value' => 'Big2',
            'option_group' => 'template',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    protected function tearDown(): void
    {
        foreach ($this->createdIds as $id) {
            try {
                LandingTestContentPurger::purge($id);
            } catch (\Throwable $e) {}
        }
        $this->createdIds = [];
        parent::tearDown();
    }
}
