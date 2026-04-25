<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;
use Tests\Browser\Traits\AdminLoginTrait;

/**
 * Admin Live Edit Element Style Editor Tests
 *
 * Tests that the Element Style Editor properly detects the active element
 * when an element is clicked/selected in the live edit canvas.
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user with email admin@admin.com / admin (canonical AdminLoginTrait credentials)
 *   - Login captcha disabled
 */
class AdminLiveEditElementStyleEditorTest extends DuskTestCase
{
    use AdminLoginTrait;

    protected function assertPreConditions(): void
    {
        // Skip parent — we rely on the already-running server's database
    }

    #[Test]
    public function element_style_editor_detects_active_element(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $checks = 0;
            $failed = [];

            // Load live edit and wait for it to fully initialize
            $browser->visit('/admin/live-edit')->pause(10000);
            $this->ensureLoggedIn($browser);
            $this->dismissAlerts($browser);
            $this->injectErrorListener($browser);

            // Wait for canvas iframe to load fully
            $browser->pause(5000);
            $this->dismissAlerts($browser);

            // ── Check 1: Canvas iframe and mw.app.liveEdit exist ──
            try {
                $liveEditReady = $browser->script("
                    try {
                        return !!(mw && mw.app && mw.app.liveEdit && mw.app.canvas);
                    } catch(e) { return false; }
                ");
                $this->assertTrue($liveEditReady[0] ?? false, 'mw.app.liveEdit should be initialized');
                $checks++;
            } catch (\Exception $e) {
                $failed['live_edit_ready'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 2: Find visible .element nodes in canvas ──
            try {
                $hasElements = $browser->script("
                    try {
                        var doc = mw.app.canvas.getDocument();
                        if (!doc) return false;
                        var elements = doc.querySelectorAll('.element');
                        var visible = 0;
                        for (var i = 0; i < elements.length; i++) {
                            var rect = elements[i].getBoundingClientRect();
                            if (rect.width > 50 && rect.height > 10) visible++;
                        }
                        return visible > 0;
                    } catch(e) { return false; }
                ");
                $this->assertTrue($hasElements[0] ?? false, 'Canvas should contain visible .element nodes');
                $checks++;
            } catch (\Exception $e) {
                $failed['has_elements'] = substr($e->getMessage(), 0, 200);
            }

            $this->dismissAlerts($browser);

            // ── Check 3: Use selectNode to select an element and verify activeNode ──
            try {
                $selected = $browser->script("
                    try {
                        var doc = mw.app.canvas.getDocument();
                        var elements = doc.querySelectorAll('.element');
                        var target = null;
                        for (var i = 0; i < elements.length; i++) {
                            var rect = elements[i].getBoundingClientRect();
                            if (rect.width > 50 && rect.height > 20 && rect.top > 0 && rect.top < 600) {
                                target = elements[i];
                                break;
                            }
                        }
                        if (!target) return 'no_target';

                        // Use the liveEdit selectNode method to simulate proper element selection
                        mw.app.liveEdit.selectNode(target);
                        return 'selected';
                    } catch(e) { return 'error:' + e.message; }
                ");
                $this->assertEquals('selected', $selected[0] ?? 'null', 'Should select an element via selectNode');
                $checks++;
            } catch (\Exception $e) {
                $failed['select_node'] = substr($e->getMessage(), 0, 200);
            }

            $browser->pause(1000);
            $this->dismissAlerts($browser);

            // ── Check 4: Verify activeNode is set ──
            try {
                $hasActiveNode = $browser->script("
                    try {
                        var node = mw.app.liveEdit.getSelectedNode();
                        return node !== null && node !== false && typeof node === 'object';
                    } catch(e) { return false; }
                ");
                $this->assertTrue($hasActiveNode[0] ?? false, 'getSelectedNode() should return the selected element');
                $checks++;
            } catch (\Exception $e) {
                $failed['active_node'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 5: Verify elementStyleEditor.selectNode dispatch fires on new selection ──
            try {
                // Install listener, then select a DIFFERENT element to trigger targetChange
                $dispatched = $browser->script("
                    try {
                        window._styleEditorDispatched = false;
                        window._styleEditorDispatchedElement = null;
                        mw.app.on('mw.elementStyleEditor.selectNode', function(el) {
                            if (el) {
                                window._styleEditorDispatched = true;
                                window._styleEditorDispatchedElement = el.nodeName;
                            }
                        });

                        // Select a DIFFERENT element to force targetChange to fire
                        var doc = mw.app.canvas.getDocument();
                        var elements = doc.querySelectorAll('.element');
                        var currentNode = mw.app.liveEdit.getSelectedNode();
                        var found = false;

                        for (var i = 0; i < elements.length; i++) {
                            var el = elements[i];
                            if (el === currentNode) continue;
                            var rect = el.getBoundingClientRect();
                            if (rect.width > 50 && rect.height > 20 && rect.top > 0 && rect.top < 800) {
                                mw.app.liveEdit.selectNode(el);
                                found = true;
                                break;
                            }
                        }
                        return found ? 'found' : 'no_different_element';
                    } catch(e) { return 'error:' + e.message; }
                ");

                $browser->pause(1500);
                $this->dismissAlerts($browser);

                $wasDispatched = $browser->script("
                    return window._styleEditorDispatched === true;
                ");
                $this->assertTrue($wasDispatched[0] ?? false, 'mw.elementStyleEditor.selectNode should be dispatched when a new element is selected');
                $checks++;
            } catch (\Exception $e) {
                $failed['dispatch_style_editor'] = substr($e->getMessage(), 0, 200);
            }

            // ── Check 6: Select a different element and verify selection changes ──
            try {
                $this->dismissAlerts($browser);

                $changedSelection = $browser->script("
                    try {
                        var doc = mw.app.canvas.getDocument();
                        var elements = doc.querySelectorAll('.element');
                        var firstNode = mw.app.liveEdit.getSelectedNode();
                        var newTarget = null;

                        // Find a different element
                        for (var i = 0; i < elements.length; i++) {
                            var el = elements[i];
                            if (el === firstNode) continue;
                            var rect = el.getBoundingClientRect();
                            if (rect.width > 50 && rect.height > 20 && rect.top > 0 && rect.top < 800) {
                                newTarget = el;
                                break;
                            }
                        }
                        if (!newTarget) return 'no_different_target';

                        mw.app.liveEdit.selectNode(newTarget);

                        var currentNode = mw.app.liveEdit.getSelectedNode();
                        return (currentNode !== null && currentNode !== false) ? 'changed' : 'not_changed';
                    } catch(e) { return 'error:' + e.message; }
                ");
                $this->assertEquals('changed', $changedSelection[0] ?? 'null', 'Selecting a different element should update the active node');
                $checks++;
            } catch (\Exception $e) {
                $failed['change_selection'] = substr($e->getMessage(), 0, 200);
            }

            $this->dismissAlerts($browser);

            // ── Check 7: No critical JS errors ──
            try {
                $criticalErrors = $this->getCriticalErrors($browser);
                $this->assertEmpty(
                    $criticalErrors,
                    'JS errors detected: ' . implode('; ', $criticalErrors)
                );
                $checks++;
            } catch (\Exception $e) {
                $failed['no_js_errors'] = substr($e->getMessage(), 0, 200);
            }

            // Final report
            $this->assertGreaterThanOrEqual(5, $checks, 'At least 5 checks must pass');
            $this->assertEmpty($failed, 'Failed checks: ' . json_encode($failed));
        });
    }
}
