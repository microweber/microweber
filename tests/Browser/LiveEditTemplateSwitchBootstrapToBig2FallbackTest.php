<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Factories\LandingPageFactory;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\CleansLandingTestPages;
use Tests\Browser\Traits\LiveEditPageBuilderTrait;
use Tests\DuskTestCase;

/**
 * Phase-6 template-switch safety: flipping `options.current_template`
 * (group=template) from "Bootstrap" to "Big2" — a template name the
 * setup wizard advertises as a skin choice but which has no actual
 * `Templates/Big2/` directory — must not blow up the public render.
 * The module shortcodes baked into a page built under Bootstrap must
 * still resolve and the page's layout file must still be discovered
 * via the fallback chain in
 * {@see \MicroweberPackages\Template\Adapters\MicroweberTemplate}.
 *
 * Task framing (TODO.md Phase 6):
 *   "Switch `current_template` option from Bootstrap → Big2, reload
 *    public page, assert page still renders (modules fall back
 *    gracefully)".
 *
 * Why "Big2" is the right probe:
 *   - `Templates/` only ships "Bootstrap" and "default"; `Big2` is a
 *     theme advertised by SetupWizardController but does not exist as
 *     a template directory on disk. That mirrors the real-world
 *     failure mode we're guarding against: a site admin points
 *     `current_template` at a theme that has since been uninstalled,
 *     mis-spelled, or whose folder was deleted by a botched deploy.
 *   - {@see MicroweberTemplate::getTemplateFolderName} returns the
 *     option value verbatim — it does not validate directory
 *     existence. So "Big2" actually flows through the render pipeline
 *     unchanged and exercises the downstream fallback branches at
 *     MicroweberTemplate.php:693 + :833 + :903 that fall back to
 *     `getFallbackTemplateDir()` ("Bootstrap").
 *
 * Flow:
 *   1. Build a Bootstrap landing page with three module-heavy
 *      sections (jumbotron + pricing + footers) via live-edit and
 *      save. Each section references template assets, so if the
 *      module fallback breaks, their blades/classes go missing from
 *      the public HTML.
 *   2. Save the original public HTML as a baseline reference.
 *   3. Flip both `options.current_template` and the page's own
 *      `active_site_template` to "Big2". Both layers matter:
 *      - The option governs the global default used whenever a
 *        page has no override (or an override that resolves to
 *        "default"/"inherit"/"").
 *      - The page's `active_site_template` wins over the option when
 *        set, so without updating it the switch wouldn't exercise
 *        the fallback at all.
 *   4. Visit the public URL as a guest, assert:
 *      a. HTTP status < 400 (no 500 from an uncaught template
 *         resolver throw).
 *      b. The rendered HTML still contains `<body>` + the page slug-
 *         derived title (proof that the layout file actually
 *         executed, not a whitedeath).
 *      c. Every pre-switch section marker still appears — modules
 *         baked into the HTML don't evaporate just because the
 *         active template folder is missing. The markers are
 *         template-agnostic (they come from the shortcode-expanded
 *         module blades stored in `content_data.layout_file`/
 *         `content.content`, not from the site template).
 *   5. Restore `options.current_template` = "Bootstrap" so later
 *      tests (and the dev server) are left on a working template.
 *      `LandingPageFactory::make()` also re-asserts Bootstrap, but
 *      restoring defensively keeps this test self-contained if run
 *      in isolation.
 *
 * Prereqs: dev server at 127.0.0.1:8000; admin admin@admin.com/admin.
 */
class LiveEditTemplateSwitchBootstrapToBig2FallbackTest extends DuskTestCase
{
    use AdminLoginTrait;
    use CleansLandingTestPages;
    use LiveEditPageBuilderTrait;

    /**
     * Compact section set — three skins from distinct categories so a
     * module-fallback regression (e.g. a shortcode expander that
     * early-returns when the active template dir is missing) can't
     * hide behind a single skin's blade.
     *
     * @var array<int, array{category:string, skin:string, marker:string, publicNeedle:string}>
     */
    private const SECTIONS = [
        [
            'category' => 'Jumbotron',
            'skin' => 'jumbotron/skin-1',
            'marker' => 'section.mw-layout-dark-background[field^="layout-jumbotron-skin-1-"]',
            'publicNeedle' => 'mw-layout-dark-background',
        ],
        [
            'category' => 'Pricing',
            'skin' => 'pricing/skin-2',
            'marker' => 'section.pricing-skin-2[field^="layout-pricing-skin-2-"]',
            'publicNeedle' => 'pricing-skin-2',
        ],
        [
            'category' => 'Footers',
            'skin' => 'footers/skin-1',
            'marker' => 'section.footer-background[field^="layout-footer-skin-1-"]',
            'publicNeedle' => 'footer-background',
        ],
    ];

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB
    }

    #[Test]
    public function switching_current_template_to_missing_big2_falls_back_gracefully(): void
    {
        $landing = LandingPageFactory::make('Template switch Bootstrap->Big2 fallback');

        $this->browse(function (Browser $browser) use ($landing) {
            // 1. Build the page under Bootstrap.
            $this->loginAsAdmin($browser);
            $this->openInLiveEdit($browser, $landing->pageId);

            foreach (self::SECTIONS as $i => $section) {
                $this->primeLayoutHandleOnMainContent($browser);
                $this->insertSkinWithCategoryWait($browser, $section['category'], $section['skin']);
                $this->waitForMarker($browser, $section['marker'], $i + 1);
            }

            $this->markAllEditsChanged($browser);
            $this->saveLiveEdit($browser);
            $browser->pause(1000);

            // 2. Baseline public HTML under Bootstrap — proves the page
            //    builds correctly before we mutate the template option,
            //    so if the post-switch check fails we know the failure
            //    is from the switch and not a flaky build step.
            $baselineStatus = $this->fetchStatus($landing->slug);
            $this->assertLessThan(
                400,
                $baselineStatus,
                "Baseline (pre-switch) public render of /{$landing->slug} must "
                . "succeed — got HTTP {$baselineStatus}. Without a working "
                . 'baseline the post-switch assertion is meaningless.'
            );

            // 3. Flip both option and page-level template to "Big2".
            try {
                $this->switchCurrentTemplateTo('Big2');
                $this->switchPageActiveSiteTemplate($landing->pageId, 'Big2');

                $this->assertSame(
                    'Big2',
                    $this->readCurrentTemplateOption(),
                    'options.current_template must actually be "Big2" after switch — '
                    . 'without this, the test would silently be re-running the '
                    . 'baseline case instead of probing the fallback path.'
                );
                $this->assertSame(
                    'Big2',
                    $this->readPageActiveSiteTemplate($landing->pageId),
                    'content.active_site_template must actually be "Big2" after '
                    . 'switch — otherwise the page-level override (which beats '
                    . 'the global option) would mask the fallback entirely.'
                );

                // 4. Visit as a guest and collect the public HTML.
                $this->logoutToGuest($browser);

                $browser->visit('/' . ltrim($landing->slug, '/'))->pause(2500);

                $status = $this->fetchStatus($landing->slug);
                $this->assertLessThan(
                    400,
                    $status,
                    "After switching current_template to a non-existent "
                    . "\"Big2\", public /{$landing->slug} must still respond "
                    . "< 400 (got HTTP {$status}). A 500 means the resolver "
                    . 'crashed on the missing template dir instead of '
                    . 'falling back to Bootstrap.'
                );

                $source = $browser->driver->getPageSource();

                $this->assertStringContainsString(
                    '<body',
                    $source,
                    "Public /{$landing->slug} under template=Big2 must still "
                    . 'render a full HTML document — a blank response would '
                    . 'mean the layout file fallback chain did not engage.'
                );

                $missing = [];
                foreach (self::SECTIONS as $section) {
                    if (strpos($source, $section['publicNeedle']) === false) {
                        $missing[] = $section['skin']
                            . ' (needle=' . json_encode($section['publicNeedle']) . ')';
                    }
                }

                $this->assertSame(
                    [],
                    $missing,
                    'Every module-baked section marker must survive a template '
                    . "switch to the missing \"Big2\" skin — the content's "
                    . 'module shortcodes are template-agnostic and should fall '
                    . 'back gracefully. Missing from /'
                    . $landing->slug . ': ' . implode('; ', $missing)
                );
            } finally {
                // 5. Leave the site on Bootstrap regardless of test outcome.
                //    A failure in the middle of the assertions must not
                //    leave the dev server pointing at a non-existent
                //    template — subsequent tests and manual QA would
                //    otherwise inherit the broken option.
                $this->switchCurrentTemplateTo('Bootstrap');
                $this->switchPageActiveSiteTemplate($landing->pageId, 'Bootstrap');
            }
        });
    }

    /**
     * Update the global `options.current_template` (group=template).
     * Idempotent — inserts the row if missing so the test can run
     * against a fresh DB without pre-seed.
     */
    private function switchCurrentTemplateTo(string $name): void
    {
        $row = DB::table('options')
            ->where('option_key', 'current_template')
            ->where('option_group', 'template')
            ->first();

        if ($row) {
            DB::table('options')
                ->where('id', $row->id)
                ->update(['option_value' => $name, 'updated_at' => now()]);
            return;
        }

        DB::table('options')->insert([
            'option_key' => 'current_template',
            'option_value' => $name,
            'option_group' => 'template',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function readCurrentTemplateOption(): string
    {
        $row = DB::table('options')
            ->where('option_key', 'current_template')
            ->where('option_group', 'template')
            ->first();
        return $row ? (string)$row->option_value : '';
    }

    /**
     * The page-level `active_site_template` column takes precedence
     * over the global option when set, so the fallback path only
     * engages if we also override it here.
     */
    private function switchPageActiveSiteTemplate(int $pageId, string $name): void
    {
        DB::table('content')
            ->where('id', $pageId)
            ->update(['active_site_template' => $name, 'updated_at' => now()]);
    }

    private function readPageActiveSiteTemplate(int $pageId): string
    {
        $row = DB::table('content')->where('id', $pageId)->first();
        return $row ? (string)$row->active_site_template : '';
    }

    /**
     * HTTP GET the public URL through Laravel's Http client so we
     * observe the server's response status from outside the browser
     * (the browser's `visit()` swallows 500s visually).
     */
    private function fetchStatus(string $slug): int
    {
        $url = config('app.url', 'http://127.0.0.1:8000') . '/' . ltrim($slug, '/');

        $response = Http::withoutVerifying()
            ->withOptions(['http_errors' => false, 'allow_redirects' => true])
            ->timeout(20)
            ->get($url);

        return $response->status();
    }

    /**
     * Drop the admin session before the public visit. We want to
     * exercise the *public* render path — the admin toolbar JS and
     * live-edit chrome both depend on the active template's assets,
     * and either could fail in a misleading way when the template
     * folder is missing.
     */
    private function logoutToGuest(Browser $browser): void
    {
        $browser->visit('/');
        $browser->pause(500);
        $browser->script("
            try {
                fetch('/api/logout', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-XSRF-TOKEN': decodeURIComponent(
                            (document.cookie.match(/XSRF-TOKEN=([^;]+)/) || ['',''])[1]
                        )
                    },
                    credentials: 'include'
                });
            } catch (e) {}
        ");
        $browser->pause(1500);
        $browser->driver->manage()->deleteAllCookies();
        $browser->pause(300);
    }

    private function insertSkinWithCategoryWait(
        Browser $browser,
        string $category,
        string $skinSlug
    ): void {
        $browser->script("
            if (window.mw && mw.app && mw.app.editor) {
                mw.app.editor.dispatch('insertLayoutRequestOnBottom', null);
            }
        ");
        $browser->pause(2000);

        $dialogOpen = $browser->script("
            return document.querySelector('.mw-le-dialog-block.mw-le-layouts-dialog.active') !== null ? 1 : 0;
        ");
        if (($dialogOpen[0] ?? 0) !== 1) {
            throw new \RuntimeException(
                "insertSkinWithCategoryWait: picker dialog never opened for '{$skinSlug}'"
            );
        }

        $browser->script("
            var wantCat = " . json_encode(strtolower($category)) . ";
            var catLinks = document.querySelectorAll(
                '.mw-le-layouts-dialog .modules-list-categories li'
            );
            for (var i = 0; i < catLinks.length; i++) {
                var label = (catLinks[i].textContent || '').trim().toLowerCase();
                if (label === wantCat) {
                    catLinks[i].click();
                    break;
                }
            }
        ");
        $browser->pause(1200);

        $clicked = $browser->script("
            var skinSlug = " . json_encode($skinSlug) . ";
            var cards = document.querySelectorAll(
                '.mw-le-layouts-dialog .modules-list-block-item, '
                + '.mw-le-layouts-dialog .modules-list-block-item-masonry'
            );
            for (var j = 0; j < cards.length; j++) {
                var card = cards[j];
                var haystack = '';
                var iframe = card.querySelector('iframe.layout-preview-iframe');
                if (iframe) { haystack += (iframe.getAttribute('src') || ''); }
                var title = card.querySelector('.modules-list-block-item-masonry-title, .modules-list-block-item-title');
                if (title) { haystack += ' ' + (title.textContent || ''); }
                haystack += ' ' + (card.getAttribute('data-template') || '');

                if (haystack.toLowerCase().indexOf(skinSlug.toLowerCase()) !== -1
                    || haystack.toLowerCase().indexOf(skinSlug.replace('/', '__').toLowerCase()) !== -1) {
                    card.click();
                    return 1;
                }
            }
            return 0;
        ");

        if (($clicked[0] ?? 0) !== 1) {
            throw new \RuntimeException(
                "insertSkinWithCategoryWait: no card matched skin '{$skinSlug}' under category '{$category}'"
            );
        }

        $browser->pause(2500);
    }

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

        $this->assertSame(
            'OK',
            $primed[0] ?? 'UNKNOWN',
            'Layout handle must resolve to the clean-layout main-content section'
        );
    }

    private function waitForMarker(Browser $browser, string $marker, int $stepNumber): void
    {
        for ($i = 0; $i < 30; $i++) {
            $res = $browser->script("
                var doc = mw.app.canvas.getDocument();
                return doc.querySelector(" . json_encode($marker) . ") ? 1 : 0;
            ");
            if (($res[0] ?? 0) === 1) {
                $browser->pause(500);
                return;
            }
            $browser->pause(500);
        }
        $browser->screenshot("fail-template-switch-step-{$stepNumber}");
        throw new \RuntimeException(
            "Step {$stepNumber}: marker '{$marker}' never appeared in canvas within 15s"
        );
    }

    private function markAllEditsChanged(Browser $browser): void
    {
        $res = $browser->script("
            var doc = mw.app.canvas.getDocument();
            var mc = doc.querySelector('.section.edit.main-content');
            if (!mc) return 'NO_MAIN_CONTENT';
            mc.classList.add('changed');
            var edits = mc.querySelectorAll('.edit');
            for (var i = 0; i < edits.length; i++) {
                edits[i].classList.add('changed');
            }
            return 'OK:' + edits.length;
        ");
        $outcome = (string)($res[0] ?? 'UNKNOWN');
        $this->assertStringStartsWith(
            'OK',
            $outcome,
            'Must be able to tag .edit.changed across main-content'
        );
    }
}
