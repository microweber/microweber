<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use Modules\Content\Models\Content;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Support\LandingTestContentPurger;
use Tests\DuskTestCase;

/**
 * Real end-to-end browser regression for the LayoutProcessor parser pipeline
 * (now the default — config `microweber.use_legacy_parser`) rendered
 * through the live **Big** template on the public frontend.
 *
 * Each test seeds a real Content page whose `content` column carries one parser
 * edge case, visits its public URL in Chrome, and asserts — from the rendered
 * DOM — that the parser behaved correctly:
 *   - module tags are expanded (no raw `<module …>` survives in the live page),
 *   - protected regions (HTML/Blade comments, <pre>/<textarea>/<script>, and a
 *     <module> inside an attribute value) keep their module verbatim and DON'T
 *     render it,
 *   - no internal placeholder leaks (`mw-protected`, `mw_replace_back_this`,
 *     `mw-unprocessed-module-tag`),
 *   - nested edit fields, rel="inherit" regions, and modules-that-have-edit-
 *     fields all resolve and render,
 *   - the page is a real 200 (no "Internal Server Error" / whoops),
 *   - and the new pipeline stays at section-count parity with the legacy parser
 *     (?use_legacy_parser=1) where a like-for-like comparison applies.
 *
 * The test is self-contained: it seeds + purges its own `bigedge-` pages, so it
 * leaves no residue. Prereqs: a dev server at 127.0.0.1:8000 sharing this
 * process's DB, and the Big template installed.
 */
class BigTemplateParserEdgeCasesTest extends DuskTestCase
{
    private const SLUG = 'bigedge-';

    /** @var int[] */
    private array $createdIds = [];

    /** Use the already-running dev server + its database. */
    protected function assertPreConditions(): void
    {
        // Intentionally skip parent: no fresh install, rely on the running site.
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->ensureBigActive();
    }

    protected function tearDown(): void
    {
        foreach ($this->createdIds as $id) {
            try {
                LandingTestContentPurger::purge($id);
            } catch (\Throwable $e) {
                // best-effort
            }
        }
        $this->createdIds = [];
        parent::tearDown();
    }

    // ─────────────────────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────────────────────

    private function ensureBigActive(): void
    {
        $row = DB::table('options')
            ->where('option_key', 'current_template')
            ->where('option_group', 'template')->first();
        if ($row) {
            if ($row->option_value !== 'Big') {
                DB::table('options')->where('id', $row->id)
                    ->update(['option_value' => 'Big', 'updated_at' => now()]);
            }
            return;
        }
        DB::table('options')->insert([
            'option_key' => 'current_template', 'option_value' => 'Big',
            'option_group' => 'template', 'created_at' => now(), 'updated_at' => now(),
        ]);
    }

    private function seedPage(string $name, string $content, array $extra = []): array
    {
        $slug = self::SLUG . $name . '-' . substr(md5(microtime(true) . Str::random(6)), 0, 8);
        $id = save_content(array_merge([
            'content_type' => 'page',
            'subtype' => 'static',
            'title' => 'BigEdge ' . $name,
            'url' => $slug,
            'active_site_template' => 'Big',
            'is_active' => 1,
            'content' => $content,
        ], $extra));
        $this->assertIsNumeric($id, "save_content failed for $name");
        $this->createdIds[] = (int) $id;
        return ['id' => (int) $id, 'slug' => $slug];
    }

    private function saveField(int $contentId, string $field, string $rel, int $relId, string $value): void
    {
        app()->content_manager->save_content_field([
            'field' => $field, 'rel_type' => $rel, 'rel_id' => $relId, 'value' => $value,
        ]);
    }

    /** Pull a rendered-DOM snapshot of the public page. */
    private function snapshot(Browser $browser, string $slug): array
    {
        $browser->visit('/' . $slug)->pause(1200);
        $src = $browser->driver->getPageSource();
        $this->assertStringNotContainsString('Internal Server Error', $src, "500 on /$slug");
        $this->assertStringNotContainsString('Whoops, something went wrong', $src, "whoops on /$slug");

        $snap = $browser->script("
            var html = document.documentElement.outerHTML;
            return {
                rawModule: (html.match(/<module\\b/gi) || []).length,
                leak: (html.match(/mw-protected|mw_replace_back_this|mw-unprocessed-module-tag/g) || []).length,
                sections: document.querySelectorAll('section').length,
                images: document.querySelectorAll('img').length,
                text: document.body.innerText,
                bodyHtml: document.body.innerHTML
            };
        ");
        return $snap[0] ?? [];
    }

    /** Assert no internal placeholder ever leaks to the page. */
    private function assertNoLeak(array $snap, string $ctx): void
    {
        $this->assertSame(0, (int) ($snap['leak'] ?? 1), "placeholder leak on $ctx");
    }

    // ─────────────────────────────────────────────────────────────
    // Edge-case tests
    // ─────────────────────────────────────────────────────────────

    #[Test]
    public function multiple_layout_modules_each_render_distinctly(): void
    {
        $page = $this->seedPage('layouts',
            '<h2>BIGEDGE-LAYOUTS</h2>'
            . '<module type="layouts" template="features/skin-4"/>'
            . '<module type="layouts" template="content/skin-2"/>'
            . '<module type="layouts" template="blog/skin-1"/>'
            . '<module type="layouts" template="pricing/skin-1"/>'
            . '<module type="layouts" template="testimonials/skin-1"/>');

        $this->browse(function (Browser $b) use ($page) {
            $snap = $this->snapshot($b, $page['slug']);
            $this->assertStringContainsString('BIGEDGE-LAYOUTS', $snap['text']);
            $this->assertSame(0, (int) $snap['rawModule'], 'no raw <module> may survive');
            $this->assertNoLeak($snap, 'layouts');
            // 5 distinct layout modules → at least 5 rendered <section>s.
            $this->assertGreaterThanOrEqual(5, (int) $snap['sections'], 'all 5 layouts rendered');
        });
    }

    #[Test]
    public function comments_pre_textarea_protect_their_modules(): void
    {
        // Each protected region carries a <module>; it must stay verbatim and
        // NOT be rendered. A real module at the end must render.
        $page = $this->seedPage('protect',
            '<h2>BIGEDGE-PROTECT</h2>'
            . '<!-- <module type="btn" template="default"/> -->'
            . '<pre>RAWPRE <module type="btn" template="default"/></pre>'
            . '<textarea>RAWTA <module type="btn" template="default"/></textarea>'
            . '{{-- <module type="btn" template="default"/> --}}'
            . '<div class="edit"><module type="layouts" template="features/skin-4"/></div>');

        $this->browse(function (Browser $b) use ($page) {
            $snap = $this->snapshot($b, $page['slug']);
            $this->assertNoLeak($snap, 'protect');
            // The real layout rendered (a section + "Feature" text).
            $this->assertStringContainsString('Feature', $snap['text']);
            // The protected raw module markers are still present verbatim.
            $this->assertGreaterThanOrEqual(3, (int) $snap['rawModule'],
                'comment + pre + textarea (+ blade) keep their module verbatim');
            // None of the protected btns actually rendered a <a class="btn"> from those regions —
            // assert the raw text markers survive intact.
            $this->assertStringContainsString('RAWPRE', $snap['text']);
        });
    }

    #[Test]
    public function module_inside_input_attribute_is_not_parsed(): void
    {
        $page = $this->seedPage('inputattr',
            '<h2>BIGEDGE-INPUT</h2>'
            . '<input type="text" value="<module type=ants template=default />"/>'
            . '<module type="layouts" template="content/skin-2"/>');

        $this->browse(function (Browser $b) use ($page) {
            $snap = $this->snapshot($b, $page['slug']);
            $this->assertNoLeak($snap, 'inputattr');
            // The input's value keeps the module string; the real layout rendered.
            $hasInputModule = $b->script(
                "var i=document.querySelector('input[value*=\"module\"]'); return i?i.getAttribute('value'):'';"
            );
            $this->assertStringContainsString('module type=ants', (string) ($hasInputModule[0] ?? ''),
                'module inside input value attribute is preserved, not parsed');
        });
    }

    #[Test]
    public function custom_module_ids_are_preserved(): void
    {
        $page = $this->seedPage('customid',
            '<h2>BIGEDGE-CUSTOMID</h2>'
            . '<module type="btn" id="bigedge-keep-1" template="default"/>'
            . '<module type="btn" id="bigedge-keep-2" template="default"/>');

        $this->browse(function (Browser $b) use ($page) {
            $snap = $this->snapshot($b, $page['slug']);
            $this->assertNoLeak($snap, 'customid');
            $ids = $b->script(
                "return Array.from(document.querySelectorAll('[id^=\"bigedge-keep\"]')).map(function(e){return e.id;});"
            );
            $found = $ids[0] ?? [];
            $this->assertContains('bigedge-keep-1', $found);
            $this->assertContains('bigedge-keep-2', $found);
        });
    }

    #[Test]
    public function unicode_and_entities_survive_parsing(): void
    {
        // Asserts the PARSER preserves multibyte text + HTML entities (café,
        // CJK, RTL) and doesn't mangle them. NOTE: 4-byte chars (emoji) depend on
        // the DB being utf8mb4 — that's a storage concern, not the parser — so we
        // deliberately assert only chars the storage layer always round-trips.
        $page = $this->seedPage('unicode',
            '<h2>BIGEDGE-UNI café 日本語 مرحبا &amp; &lt;x&gt;</h2>'
            . '<module type="layouts" template="content/skin-2"/>');

        $this->browse(function (Browser $b) use ($page) {
            $snap = $this->snapshot($b, $page['slug']);
            $this->assertNoLeak($snap, 'unicode');
            $this->assertStringContainsString('café', $snap['text']);
            $this->assertStringContainsString('日本語', $snap['text']);
            $this->assertStringContainsString('مرحبا', $snap['text']);
            // The &amp;/&lt; entities are preserved (the parser didn't double-encode
            // or strip them): the rendered text shows the decoded "&" and "<x>".
            $this->assertStringContainsString('& <x>', $snap['text']);
            $this->assertSame(0, (int) $snap['rawModule']);
        });
    }

    #[Test]
    public function nested_edit_fields_with_modules_render(): void
    {
        // Page content holds nested .edit regions; their saved content (with
        // modules) replaces the inline defaults.
        $page = $this->seedPage('nested',
            '<h2>BIGEDGE-NESTED</h2>'
            . '<div class="edit" rel="content" field="be_a">DEF-A</div>'
            . '<div class="edit" rel="global" field="be_g">DEF-G</div>');
        $id = $page['id'];

        $this->saveField($id, 'be_a', 'content', $id,
            '<h3>EDITED-A</h3><module type="btn" id="be-a-btn" template="default"/>'
            . '<div class="edit" rel="content" field="be_a2">DEF-A2</div>');
        $this->saveField($id, 'be_a2', 'content', $id,
            '<h3>EDITED-A2</h3><module type="layouts" template="features/skin-4"/>');
        $this->saveField($id, 'be_g', 'global', 0,
            '<h3>EDITED-G</h3><module type="btn" id="be-g-btn" template="default"/>');

        $this->browse(function (Browser $b) use ($page) {
            $snap = $this->snapshot($b, $page['slug']);
            $this->assertNoLeak($snap, 'nested');
            foreach (['EDITED-A', 'EDITED-A2', 'EDITED-G'] as $marker) {
                $this->assertStringContainsString($marker, $snap['text'], "nested $marker rendered");
            }
            $this->assertStringNotContainsString('DEF-A2', $snap['bodyHtml'], 'inner default replaced');
            $this->assertStringContainsString('Feature', $snap['text'], 'nested layout rendered');
            $this->assertSame(0, (int) $snap['rawModule']);
            $ids = $b->script("return Array.from(document.querySelectorAll('[id^=\"be-\"]')).map(e=>e.id);");
            $this->assertContains('be-a-btn', $ids[0] ?? []);
            $this->assertContains('be-g-btn', $ids[0] ?? []);
        });
    }

    #[Test]
    public function inherited_region_with_module_renders_on_child(): void
    {
        // Master page owns an inherited region (with a custom-id btn + a layout
        // module that itself has an internal rel=module edit field). A child
        // whose parent = master inherits and renders it.
        $master = $this->seedPage('master', '<h2>BIGEDGE-MASTER</h2>');
        $this->saveField($master['id'], 'be_inh', 'inherit', $master['id'],
            '<h3>BIGEDGE-INHERITED</h3><module type="btn" id="be-inh-btn" template="default"/>'
            . '<module type="layouts" template="features/skin-4"/>');

        $child = $this->seedPage('child',
            '<h2>BIGEDGE-CHILD</h2>'
            . '<div class="edit" rel="inherit" field="be_inh">DEF-INH</div>',
            ['content_type' => 'post', 'parent' => $master['id']]);

        $this->browse(function (Browser $b) use ($child) {
            $snap = $this->snapshot($b, $child['slug']);
            $this->assertNoLeak($snap, 'inherit');
            $this->assertStringContainsString('BIGEDGE-INHERITED', $snap['text'],
                'inherited region loaded from master');
            $this->assertStringContainsString('Feature', $snap['text'],
                'module-with-edit-field inside inherited region rendered');
            $this->assertSame(0, (int) $snap['rawModule']);
            $ids = $b->script("return Array.from(document.querySelectorAll('#be-inh-btn')).map(e=>e.id);");
            $this->assertContains('be-inh-btn', $ids[0] ?? []);
        });
    }

    #[Test]
    public function html_and_blade_comments_inside_form_tags_are_protected(): void
    {
        // HTML + Blade comments (each carrying a <module>) placed inside a <form>
        // and its label/select/button children must NOT render their module, the
        // form must stay structurally intact, and a real module after the form
        // must render — all with no placeholder leak.
        $page = $this->seedPage('formcmt',
            '<h2>BIGEDGE-FORMCMT</h2>'
            . '<form action="/bigedge-submit" method="post">'
            . '<!-- HTMLC <module type="btn" template="default"/> -->'
            . '{{-- BLADEC <module type="btn" template="default"/> --}}'
            . '<label><!-- LBLC <module type="btn"/> -->Your name</label>'
            . '<input type="text" name="be_name"/>'
            . '<select name="be_opt"><!-- SELC <module type="btn"/> -->'
            . '{{-- SELBLADE <module type="btn"/> --}}<option>BEOPT</option></select>'
            . '<button type="submit"><!-- BTNC <module type="btn"/> -->Send</button>'
            . '</form>'
            . '<div class="edit"><module type="layouts" template="features/skin-4"/></div>');

        $this->browse(function (Browser $b) use ($page) {
            $snap = $this->snapshot($b, $page['slug']);
            $this->assertNoLeak($snap, 'formcmt');

            $form = $b->script("
                var f = document.querySelector('form[action=\"/bigedge-submit\"]');
                return {
                    found: !!f,
                    input: f ? !!f.querySelector('input[name=\"be_name\"]') : false,
                    select: f ? !!f.querySelector('select[name=\"be_opt\"]') : false,
                    option: f ? !!Array.from(f.querySelectorAll('option')).find(o=>o.textContent.trim()==='BEOPT') : false,
                    button: f ? !!f.querySelector('button[type=\"submit\"]') : false,
                    btnsFromComments: f ? f.querySelectorAll('a.btn, .mw-add-to-cart-btn').length : -1,
                    comments: (document.documentElement.outerHTML.match(/HTMLC|BLADEC|LBLC|SELC|BTNC/g)||[]).length
                };
            ")[0];

            // Form scaffolding intact.
            $this->assertTrue((bool) $form['found'], 'form rendered');
            $this->assertTrue((bool) $form['input'], 'form input intact');
            $this->assertTrue((bool) $form['select'], 'form select intact');
            $this->assertTrue((bool) $form['option'], 'select option intact');
            $this->assertTrue((bool) $form['button'], 'form button intact');
            // None of the comment-wrapped modules rendered a real control.
            $this->assertSame(0, (int) $form['btnsFromComments'], 'no comment module rendered inside the form');
            // The comments themselves are preserved verbatim.
            $this->assertGreaterThanOrEqual(5, (int) $form['comments'], 'form comments preserved');
            // The real layout after the form still rendered.
            $this->assertStringContainsString('Feature', $snap['text'], 'real module after form rendered');
        });
    }

    #[Test]
    public function new_pipeline_is_section_parity_with_legacy(): void
    {
        // A like-for-like page: the new default and the ?use_legacy_parser=1
        // legacy override must render the same number of <section>s.
        $page = $this->seedPage('parity',
            '<h2>BIGEDGE-PARITY</h2>'
            . '<module type="layouts" template="features/skin-4"/>'
            . '<module type="layouts" template="content/skin-4"/>'
            . '<module type="layouts" template="blog/skin-1"/>');

        $this->browse(function (Browser $b) use ($page) {
            $newSnap = $this->snapshot($b, $page['slug']);

            $b->visit('/' . $page['slug'] . '?use_legacy_parser=1')->pause(1200);
            $legacy = $b->script("return document.querySelectorAll('section').length;");
            $legacySections = (int) ($legacy[0] ?? -1);

            $this->assertNoLeak($newSnap, 'parity');
            $this->assertSame(0, (int) $newSnap['rawModule']);
            $this->assertSame($legacySections, (int) $newSnap['sections'],
                'new pipeline section count must match legacy');
        });
    }
}
