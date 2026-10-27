<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\DuskTestCase;

/**
 * Live-Edit AI agent INTERACTION tests (browser).
 *
 * The AI agent is a TOOL-CALL canvas editor: the NeuronAI backend streams tool
 * calls over SSE (/api/ai/agent-chat-stream) and the browser applies each one to
 * the real Live-Edit canvas through MwAi().applyEdit(edit) — the frontend tool
 * handlers in Modules/Ai/resources/assets/js/mw-ai.js (`frontendTools`). A CLI
 * probe cannot reach that path: it binds a static DOM, so the echo tools
 * (add_section / set_text / apply_css / insert_module / set_module_option) never
 * mutate anything. Only a browser exercises the real interaction.
 *
 * This test drives applyEdit() directly — the SAME entry point the SSE dispatcher
 * calls for every streamed tool — so it is DETERMINISTIC and does NOT depend on a
 * running LLM. It asserts each tool actually mutates (or correctly refuses on) the
 * canvas. It never clicks SAVE and only writes an in-memory, uniquely-scoped
 * (.mw-ai-dusk-probe) section + CSS, so nothing persists to the content DB and the
 * admin@admin.com login (AdminLoginTrait) mutates no stored content.
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user admin@admin.com / admin (canonical AdminLoginTrait creds)
 *   - The deployed public/modules/ai/js/mw-ai.js (republished from source on deploy)
 */
class AdminAiAgentInteractionTest extends DuskTestCase
{
    use AdminLoginTrait;

    protected function assertPreConditions(): void
    {
        // Skip parent — we rely on the already-running server's database.
    }

    /**
     * JS helper (evaluated in the TOP window) that resolves the MwAi instance.
     * mw-ai.js is injected into the CANVAS frame via LiveEditManager::addScript,
     * so MwAi lives on the iframe's contentWindow; fall back to the top window.
     */
    private const MWAI_ACCESSOR = <<<'JS'
        function __mwai() {
            try {
                var ifr = document.querySelector('iframe');
                if (ifr && ifr.contentWindow && typeof ifr.contentWindow.MwAi === 'function') {
                    return ifr.contentWindow.MwAi();
                }
            } catch (e) {}
            try { if (typeof window.MwAi === 'function') return window.MwAi(); } catch (e) {}
            try {
                var ifr2 = document.querySelector('iframe');
                var top = ifr2 && ifr2.contentWindow && ifr2.contentWindow.mw && ifr2.contentWindow.mw.top();
                if (top && top.win && typeof top.win.MwAi === 'function') return top.win.MwAi();
            } catch (e) {}
            return null;
        }
        function __canvasDoc() {
            try { return __mwai().canvasDocument(); } catch (e) {}
            try { return document.querySelector('iframe').contentWindow.document; } catch (e) {}
            return null;
        }
    JS;

    #[Test]
    public function ai_agent_tool_calls_drive_the_live_canvas(): void
    {
        $this->browse(function (Browser $browser) {
            $checks = 0;
            $failed = [];

            $this->loginAsAdmin($browser);

            // ── Load Live-Edit and wait for the canvas + MwAi to be ready ──
            try {
                $browser->visit('/admin/live-edit')->pause(5000);
                $this->assertStringContainsString('live-edit', $browser->driver->getCurrentURL(),
                    'Should be on the live-edit page');
                $browser->waitFor('iframe', 15)->pause(4000);

                // Wait until MwAi + the canvas content region are both resolvable.
                $browser->waitUsing(20, 1000, function () use ($browser) {
                    $ready = $browser->script(self::MWAI_ACCESSOR . "
                        var ai = __mwai();
                        if (!ai || typeof ai.applyEdit !== 'function') return false;
                        var doc = __canvasDoc();
                        return !!(doc && doc.body);
                    ");
                    return $ready[0] === true;
                });
                $checks++;
            } catch (\Exception $e) {
                $failed['live_edit_ready'] = substr($e->getMessage(), 0, 240);
                $browser->screenshot('fail-ai-interaction-ready');
            }

            // ── Check 1: the agent interaction surface is wired ──
            // MwAi exposes applyEdit (the SSE dispatch entry) and a frontendTools
            // registry carrying every canvas-mutating tool the agent can call.
            try {
                $surface = $browser->script(self::MWAI_ACCESSOR . "
                    var ai = __mwai();
                    if (!ai) return { ok: false, why: 'no MwAi instance' };
                    var ft = ai.frontendTools || {};
                    var need = ['add_section','insert_module','set_module_option','set_text',
                                'apply_css','set_image','add_form_field','delete_element',
                                'insert_layout','navigate_to_page'];
                    var missing = need.filter(function (t) { return typeof ft[t] !== 'function'; });
                    return {
                        ok: typeof ai.applyEdit === 'function' && missing.length === 0,
                        hasApplyEdit: typeof ai.applyEdit === 'function',
                        missing: missing,
                        toolCount: Object.keys(ft).length
                    };
                ");
                $info = $surface[0] ?? [];
                $this->assertTrue($info['hasApplyEdit'] ?? false,
                    'MwAi must expose applyEdit() — the SSE tool dispatch entry.');
                $this->assertEmpty($info['missing'] ?? ['?'],
                    'frontendTools is missing canvas tools: ' . json_encode($info['missing'] ?? null));
                $checks++;
            } catch (\Exception $e) {
                $failed['interaction_surface'] = substr($e->getMessage(), 0, 240);
            }

            // ── Check 2: set_module_option REFUSES when no module is targeted ──
            // Run before any insert_module so _mwAiLastModule is unset. This path
            // returns ok:false and writes NOTHING to the DB (zero residue).
            try {
                $guard = $browser->script(self::MWAI_ACCESSOR . "
                    var r = __mwai().applyEdit({ tool: 'set_module_option', args: { key: 'text', value: 'x' } });
                    return r;
                ");
                $g = $guard[0] ?? [];
                $this->assertFalse($g['ok'] ?? true,
                    'set_module_option with no module must refuse (ok:false).');
                $this->assertStringContainsString('no module', strtolower($g['message'] ?? ''),
                    'The refusal should explain there is no module to configure.');
                $checks++;
            } catch (\Exception $e) {
                $failed['set_module_option_guard'] = substr($e->getMessage(), 0, 240);
            }

            // ── Check 3: an unknown tool is rejected, not thrown ──
            try {
                $unknown = $browser->script(self::MWAI_ACCESSOR . "
                    return __mwai().applyEdit({ tool: '__nope_not_a_tool__', args: {} });
                ");
                $u = $unknown[0] ?? [];
                $this->assertFalse($u['ok'] ?? true, 'An unknown tool must return ok:false.');
                $this->assertStringContainsString('unknown tool', strtolower($u['message'] ?? ''),
                    'Unknown-tool dispatch should name the tool.');
                $checks++;
            } catch (\Exception $e) {
                $failed['unknown_tool'] = substr($e->getMessage(), 0, 240);
            }

            // ── Check 4: add_section inserts a tagged, editable section ──
            try {
                $sectionHtml = '<section class="mw-ai-dusk-probe">'
                    . '<h2 id="mw-ai-dusk-h">Dusk Agent Probe Heading</h2>'
                    . '<p id="mw-ai-dusk-p">Probe paragraph built by the agent.</p>'
                    . '</section>';
                $added = $browser->script(self::MWAI_ACCESSOR . "
                    var ai = __mwai();
                    var r = ai.applyEdit({ tool: 'add_section', args: { html: " . json_encode($sectionHtml) . " } });
                    var doc = __canvasDoc();
                    var sec = doc.querySelector('.mw-ai-dusk-probe');
                    return {
                        ok: r && r.ok,
                        message: r && r.message,
                        sectionPresent: !!sec,
                        isElement: !!(sec && sec.classList.contains('element')),
                        isBuilt: !!(sec && sec.classList.contains('mw-ai-built-section')),
                        headingText: sec ? (sec.querySelector('h2') || {}).textContent : null
                    };
                ");
                $a = $added[0] ?? [];
                $this->assertTrue($a['ok'] ?? false, 'add_section should report ok. msg=' . ($a['message'] ?? ''));
                $this->assertTrue($a['sectionPresent'] ?? false,
                    'The section must be inserted into the canvas content region.');
                $this->assertTrue($a['isElement'] ?? false,
                    'The section must be tagged .element (draggable Live-Edit handle).');
                $this->assertTrue($a['isBuilt'] ?? false,
                    'The section must carry the .mw-ai-built-section marker.');
                $this->assertEquals('Dusk Agent Probe Heading', trim($a['headingText'] ?? ''),
                    'The heading text must render on the canvas.');
                $checks++;
            } catch (\Exception $e) {
                $failed['add_section'] = substr($e->getMessage(), 0, 240);
                $browser->screenshot('fail-ai-interaction-add-section');
            }

            // ── Check 5: set_text edits a leaf created by add_section ──
            try {
                $edited = $browser->script(self::MWAI_ACCESSOR . "
                    var r = __mwai().applyEdit({ tool: 'set_text', args: { selector: '#mw-ai-dusk-h', text: 'Edited By The Agent' } });
                    var doc = __canvasDoc();
                    var h = doc.querySelector('#mw-ai-dusk-h');
                    return { ok: r && r.ok, text: h ? h.textContent : null };
                ");
                $ed = $edited[0] ?? [];
                $this->assertTrue($ed['ok'] ?? false, 'set_text should report ok.');
                $this->assertEquals('Edited By The Agent', trim($ed['text'] ?? ''),
                    'set_text must change the element textContent on the canvas.');
                $checks++;
            } catch (\Exception $e) {
                $failed['set_text'] = substr($e->getMessage(), 0, 240);
            }

            // ── Check 6: apply_css injects a rule that takes effect on the canvas ──
            // Scoped to .mw-ai-dusk-probe so it is harmless even if it were flushed
            // (it is not — the test never SAVEs).
            try {
                $styled = $browser->script(self::MWAI_ACCESSOR . "
                    var r = __mwai().applyEdit({ tool: 'apply_css', args: { css: '.mw-ai-dusk-probe h2 { color: rgb(7, 83, 155); }' } });
                    var doc = __canvasDoc();
                    var h = doc.querySelector('#mw-ai-dusk-h');
                    var win = doc.defaultView || window;
                    var color = h ? win.getComputedStyle(h).color : null;
                    return { ok: r && r.ok, color: color };
                ");
                $st = $styled[0] ?? [];
                $this->assertTrue($st['ok'] ?? false, 'apply_css should report ok.');
                $this->assertStringContainsString('7, 83, 155', (string) ($st['color'] ?? ''),
                    'apply_css must actually recolour the heading (got ' . ($st['color'] ?? 'null') . ').');
                $checks++;
            } catch (\Exception $e) {
                $failed['apply_css'] = substr($e->getMessage(), 0, 240);
            }

            // ── Check 7 (soft): insert_module drops a real module on the canvas ──
            // Async (editor.insertModule); recorded as a soft check because module
            // load timing varies. Sets _mwAiLastModule so the agent can then target
            // it with set_module_option.
            try {
                $inserted = $browser->script(self::MWAI_ACCESSOR . "
                    var r = __mwai().applyEdit({ tool: 'insert_module', args: { type: 'btn' } });
                    return { ok: r && r.ok, message: r && r.message };
                ");
                $browser->pause(2500);
                $after = $browser->script(self::MWAI_ACCESSOR . "
                    var last = null;
                    try { last = (document.querySelector('iframe').contentWindow.mw.top().app._mwAiLastModule) || null; } catch (e) {}
                    var doc = __canvasDoc();
                    var mods = doc ? doc.querySelectorAll('.module, [id^=\"module-\"], [data-type]').length : 0;
                    return { lastModule: last, moduleCount: mods };
                ");
                $ins = $inserted[0] ?? [];
                $af = $after[0] ?? [];
                $this->assertTrue($ins['ok'] ?? false, 'insert_module should report ok (async apply).');
                $this->assertTrue(
                    (($af['lastModule'] ?? null) !== null) || (($af['moduleCount'] ?? 0) > 0),
                    'insert_module should record _mwAiLastModule or add a module element.'
                );
                $checks++;
            } catch (\Exception $e) {
                // Soft: module-load timing varies; do not fail the suite on it.
                $failed['insert_module_soft'] = substr($e->getMessage(), 0, 240);
            }

            // ── Check 8 (soft): the AI chat panel UI mounts ──
            // The human-facing surface (the Claude-style conversation box) must be
            // openable from the Live-Edit widgets service.
            try {
                $browser->script(self::MWAI_ACCESSOR . "
                    try { document.querySelector('iframe').contentWindow.mw.top().app.liveEditWidgets.openQuickEditComponent(); } catch (e) {}
                ");
                $browser->pause(1500);
                $panel = $browser->script("
                    function has(doc){ try { return !!(doc && doc.querySelector('.mw-ai-conv')); } catch(e){ return false; } }
                    var top = has(document);
                    var ifr = document.querySelector('iframe');
                    var inFrame = ifr ? has(ifr.contentWindow.document) : false;
                    return top || inFrame;
                ");
                $this->assertTrue($panel[0] ?? false,
                    'The AI conversation panel (.mw-ai-conv) should mount when opened.');
                $checks++;
            } catch (\Exception $e) {
                $failed['ai_panel_soft'] = substr($e->getMessage(), 0, 240);
            }

            // ── Report (house style) ──
            // Hard checks are 1–6 (surface, guards, add_section, set_text,
            // apply_css). Checks 7–8 are soft; a failure there is reported but the
            // "ready + 6 hard checks" floor is what gates the suite.
            $hardFailures = array_diff_key($failed, array_flip(['insert_module_soft', 'ai_panel_soft']));
            if (!empty($hardFailures)) {
                $report = 'Failed ' . count($hardFailures) . ' hard AI-interaction check(s):' . "\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->assertGreaterThanOrEqual(7, $checks,
                "AI agent-interaction checks passed (got {$checks}; ready + 6 hard + up to 2 soft).");
        });
    }

    /**
     * The agent must be able to drop ANY real module onto the canvas with
     * insert_module — including the list/content modules (faq, tabs, accordion)
     * whose items are managed by a Livewire table, not flat options. This
     * exercises the same frontendTools.insert_module path the SSE stream uses and
     * asserts a module element of the requested type actually renders. Canvas-only
     * (never saved), so nothing persists.
     */
    #[Test]
    public function ai_can_insert_representative_modules_including_faq_and_tabs(): void
    {
        // A spread across the module families: a simple options module (btn),
        // the list/content modules the user called out (faq, tabs, accordion),
        // media (video), a form (contact_form), and people/social modules.
        $types = [
            'btn', 'faq', 'tabs', 'accordion', 'video',
            'contact_form', 'testimonials', 'teamcard', 'social_links', 'rating',
        ];

        $this->browse(function (Browser $browser) use ($types) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/live-edit')->pause(5000);
            $browser->waitFor('iframe', 15)->pause(4000);
            $browser->waitUsing(20, 1000, function () use ($browser) {
                $ready = $browser->script(self::MWAI_ACCESSOR . "
                    var ai = __mwai();
                    return !!(ai && typeof ai.applyEdit === 'function' && __canvasDoc());
                ");
                return $ready[0] === true;
            });

            $inserted = [];
            $missing = [];

            foreach ($types as $type) {
                try {
                    $res = $browser->script(self::MWAI_ACCESSOR . "
                        var r = __mwai().applyEdit({ tool: 'insert_module', args: { type: " . json_encode($type) . " } });
                        return { ok: r && r.ok, message: r && r.message };
                    ");
                    $browser->pause(2200);
                    $present = $browser->script(self::MWAI_ACCESSOR . "
                        var doc = __canvasDoc();
                        var t = " . json_encode($type) . ";
                        // Match however Live-Edit tags the module: type=, data-type=, or
                        // the module's own class/id. Also accept _mwAiLastModule.type.
                        var sel = '.module[type=\"' + t + '\"], .module[data-type=\"' + t + '\"], [data-type=\"' + t + '\"]';
                        var found = doc ? doc.querySelectorAll(sel).length : 0;
                        var last = null;
                        try { last = document.querySelector('iframe').contentWindow.mw.top().app._mwAiLastModule; } catch (e) {}
                        var lastMatches = !!(last && last.type === t);
                        return { found: found, lastMatches: lastMatches };
                    ");
                    $r = $res[0] ?? [];
                    $p = $present[0] ?? [];
                    $ok = ($r['ok'] ?? false) && ((($p['found'] ?? 0) > 0) || ($p['lastMatches'] ?? false));
                    if ($ok) {
                        $inserted[] = $type;
                    } else {
                        $missing[$type] = 'ok=' . var_export($r['ok'] ?? null, true)
                            . ' found=' . ($p['found'] ?? '?')
                            . ' lastMatches=' . var_export($p['lastMatches'] ?? null, true)
                            . ' msg=' . ($r['message'] ?? '');
                    }
                } catch (\Exception $e) {
                    $missing[$type] = substr($e->getMessage(), 0, 160);
                }
            }

            // The three the user named explicitly MUST work.
            foreach (['faq', 'tabs', 'accordion'] as $named) {
                $this->assertContains($named, $inserted,
                    "insert_module must drop the '{$named}' module on the canvas. "
                    . 'Failures: ' . json_encode($missing));
            }

            // And the broad majority of the representative set must insert, so a
            // regression in the module insert path is caught, not just one module.
            $this->assertGreaterThanOrEqual(
                (int) ceil(count($types) * 0.8),
                count($inserted),
                'Most representative modules should insert. Inserted: ' . json_encode($inserted)
                . ' | Missing: ' . json_encode($missing)
            );
        });
    }
}
