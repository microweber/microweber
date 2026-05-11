<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-179 / AI-267 + AI-271 (2026-05-11) — Phase 1 UX batch.
 *
 *   AI-267 — Checkout breadcrumb / progress indicator.
 *            Customers can't tell where they are in the checkout
 *            flow on mobile. Filament Wizard already renders a
 *            header with step icons + labels but the default
 *            styling looks like a navigation menu, not progress.
 *            Cycle-179 enhances the existing `.fi-fo-wizard-
 *            header` into a true breadcrumb: indicator circles
 *            via `--touch-target-min` (44×44 — WCAG 2.5.5),
 *            connecting horizontal line between steps,
 *            distinct fi-completed / fi-active / future states
 *            using design-system tokens, horizontal scroll-snap
 *            on <768px viewports.
 *
 *   AI-271 — Save → "Save & Publish" rename + 30s draft auto-save.
 *            The "SAVE" button label doesn't convey that the
 *            action publishes changes to the live site
 *            immediately — users reported expecting a separate
 *            publish step. Fix: rename to "Save & Publish",
 *            update aria-label, add 30-second background draft
 *            auto-save that fires silently when the user has
 *            edited since the last save. Failure still toasts
 *            so lost-work is surfaced.
 */
class Ai267Ai271CheckoutBreadcrumbAndSavePublishContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function source_carries_cycle_179_anchor(): void
    {
        $blade = $this->read('Modules/Checkout/resources/views/livewire/checkout-wizard/index.blade.php');
        $vue = $this->read('packages/frontend-assets/resources/assets/ui/components/Toolbar/SaveButton.vue');
        $this->assertMatchesRegularExpression('/[Cc]ycle-179/', $blade,
            'checkout-wizard index.blade.php MUST carry the cycle-179 anchor.');
        $this->assertStringContainsString('AI-267', $blade,
            'checkout-wizard index.blade.php MUST carry the AI-267 anchor.');
        $this->assertMatchesRegularExpression('/[Cc]ycle-179/', $vue,
            'SaveButton.vue MUST carry the cycle-179 anchor.');
        $this->assertStringContainsString('AI-271', $vue,
            'SaveButton.vue MUST carry the AI-271 anchor.');
    }

    #[Test]
    public function ai_267_breadcrumb_uses_design_tokens(): void
    {
        $src = $this->read('Modules/Checkout/resources/views/livewire/checkout-wizard/index.blade.php');
        // Indicator circle sized via --touch-target-min.
        $this->assertMatchesRegularExpression(
            '/\.fi-fo-wizard-header-step-button[\s\S]{0,600}width:\s*var\(--touch-target-min/m',
            $src,
            'breadcrumb indicator circle MUST use var(--touch-target-min) '
            . 'for width — single source of truth + WCAG 2.5.5 floor.'
        );
        // Active step uses --color-primary.
        $this->assertMatchesRegularExpression(
            '/\.fi-fo-wizard-header-step\.fi-active\s+\.fi-fo-wizard-header-step-button[\s\S]{0,400}background-color:\s*var\(--color-primary/m',
            $src,
            'active step indicator MUST fill with var(--color-primary) '
            . 'so the user knows where they are in the flow.'
        );
        // Completed step uses --color-primary (visited marker).
        $this->assertMatchesRegularExpression(
            '/\.fi-fo-wizard-header-step\.fi-completed\s+\.fi-fo-wizard-header-step-button[\s\S]{0,400}background-color:\s*var\(--color-primary/m',
            $src,
            'completed step indicator MUST fill with var(--color-primary) '
            . 'so visited steps look distinct from upcoming ones.'
        );
    }

    #[Test]
    public function ai_267_connecting_line_between_steps(): void
    {
        $src = $this->read('Modules/Checkout/resources/views/livewire/checkout-wizard/index.blade.php');
        // ::before pseudo-element creates the connecting line.
        $this->assertMatchesRegularExpression(
            '/\.fi-fo-wizard-header-step:not\(:first-child\)::before[\s\S]{0,500}background-color:\s*var\(--color-border/m',
            $src,
            'breadcrumb MUST render a connecting line between step '
            . 'indicators (::before pseudo-element with '
            . '--color-border base).'
        );
        // Past+current connecting lines colored --color-primary.
        $this->assertMatchesRegularExpression(
            '/\.fi-fo-wizard-header-step\.fi-completed::before[\s\S]{0,500}background-color:\s*var\(--color-primary/m',
            $src,
            'connecting line BEFORE completed/active steps MUST be '
            . 'var(--color-primary) so the progress fill is visible.'
        );
    }

    #[Test]
    public function ai_267_mobile_scroll_snap(): void
    {
        $src = $this->read('Modules/Checkout/resources/views/livewire/checkout-wizard/index.blade.php');
        // Horizontal scroll-snap on <768px so all 5 steps stay
        // reachable on 390px viewport without wrapping.
        $this->assertMatchesRegularExpression(
            '/@media\s*\(max-width:\s*768px\)[\s\S]{0,1200}scroll-snap-type:\s*x\s+mandatory/m',
            $src,
            'breadcrumb @media (max-width: 768px) MUST set '
            . 'scroll-snap-type: x mandatory so all 5 steps are '
            . 'reachable on 390px viewport.'
        );
    }

    #[Test]
    public function ai_271_button_relabeled_save_and_publish(): void
    {
        $vue = $this->read('packages/frontend-assets/resources/assets/ui/components/Toolbar/SaveButton.vue');
        // Visible label.
        $this->assertStringContainsString('Save &amp; Publish', $vue,
            'SaveButton.vue MUST display "Save & Publish" as the '
            . 'visible label (was "SAVE") so users understand the '
            . 'action publishes changes immediately.');
        // Old label gone.
        $this->assertStringNotContainsString('>SAVE<', $vue,
            'SaveButton.vue MUST NOT carry the old "SAVE" label.');
        // aria-label updated.
        $this->assertStringContainsString('aria-label="Save and publish page (Ctrl+S)"', $vue,
            'SaveButton.vue MUST update aria-label to "Save and '
            . 'publish page (Ctrl+S)" to match new visible label.');
    }

    #[Test]
    public function ai_271_30s_auto_save_interval(): void
    {
        $vue = $this->read('packages/frontend-assets/resources/assets/ui/components/Toolbar/SaveButton.vue');
        // 30000ms interval defined.
        $this->assertStringContainsString('AUTO_SAVE_INTERVAL_MS = 30000', $vue,
            'SaveButton.vue MUST define AUTO_SAVE_INTERVAL_MS as '
            . '30000 (30 seconds — PM spec).');
        // setInterval invocation.
        $this->assertMatchesRegularExpression(
            '/setInterval\([\s\S]{0,400}AUTO_SAVE_INTERVAL_MS\)/m',
            $vue,
            'SaveButton.vue MUST schedule auto-save via '
            . 'setInterval(_, AUTO_SAVE_INTERVAL_MS).'
        );
        // Auto-save calls save with silent: true.
        $this->assertMatchesRegularExpression(
            '/self\.save\(\s*\{\s*silent:\s*true\s*\}/m',
            $vue,
            'auto-save tick MUST call save({silent: true}) so the '
            . 'success toast does not spam every 30 seconds.'
        );
    }

    #[Test]
    public function ai_271_dirty_flag_guards_auto_save(): void
    {
        $vue = $this->read('packages/frontend-assets/resources/assets/ui/components/Toolbar/SaveButton.vue');
        // _dirty state tracked.
        $this->assertStringContainsString('_dirty', $vue,
            'SaveButton.vue MUST track a _dirty flag so auto-save '
            . 'only fires when the user has edited since last save.');
        // markDirty method.
        $this->assertMatchesRegularExpression(
            '/markDirty\s*\(\s*\)\s*\{[\s\S]{0,200}this\.\$data\._dirty\s*=\s*true/m',
            $vue,
            'SaveButton.vue MUST expose a markDirty() method that '
            . 'sets _dirty = true.'
        );
        // Auto-save tick checks dirty.
        $this->assertMatchesRegularExpression(
            '/setInterval\([\s\S]{0,400}if\s*\(!self\.\$data\._dirty\)\s*return/m',
            $vue,
            'auto-save tick MUST short-circuit when _dirty is false '
            . '(no work to save).'
        );
        // _dirty reset on successful save.
        $this->assertMatchesRegularExpression(
            '/self\.\$data\._dirty\s*=\s*false/m',
            $vue,
            'save() MUST reset _dirty = false after a successful save '
            . 'so the next auto-save tick correctly short-circuits '
            . 'until the user edits again.'
        );
    }

    #[Test]
    public function ai_271_silent_mode_suppresses_success_toast(): void
    {
        $vue = $this->read('packages/frontend-assets/resources/assets/ui/components/Toolbar/SaveButton.vue');
        // save(options) accepts silent flag.
        $this->assertMatchesRegularExpression(
            '/save\s*\(\s*options\s*\)[\s\S]{0,300}silent\s*=\s*options\.silent\s*===\s*true/m',
            $vue,
            'save() MUST accept a silent flag via options.silent so '
            . 'auto-save can suppress the success toast.'
        );
        // Silent path returns BEFORE the success toast — assert
        // (a) silent guard exists and (b) success toast exists
        // AFTER the silent return (by string-index comparison).
        $this->assertMatchesRegularExpression(
            '/if\s*\(silent\)\s*\{[\s\S]{0,400}return;\s*\}/m',
            $vue,
            'save() MUST contain `if (silent) { return; }` guard so '
            . 'auto-save can suppress the success toast.'
        );
        $silentReturnPos = preg_match('/if\s*\(silent\)\s*\{[\s\S]{0,400}return;\s*\}/m', $vue, $m, PREG_OFFSET_CAPTURE);
        $silentEnd = $silentReturnPos ? $m[0][1] + strlen($m[0][0]) : false;
        $successPos = strpos($vue, 'mw.notification.success', $silentEnd ?: 0);
        $this->assertNotFalse($successPos,
            'save() MUST call mw.notification.success() AFTER the '
            . 'silent-return guard so non-silent saves still toast.');
        // Failure toast still fires on silent saves — lost-work
        // alert is important.
        $this->assertMatchesRegularExpression(
            '/Failure path[\s\S]{0,400}mw\.notification\.error/m',
            $vue,
            'save() failure path MUST still fire mw.notification.error '
            . 'even on silent auto-save so the user is alerted to '
            . 'lost work.'
        );
    }

    #[Test]
    public function built_bundle_carries_relabel_and_auto_save(): void
    {
        $rel = 'public/vendor/microweber-packages/frontend-assets/build/live-edit-app.js';
        $path = base_path($rel);
        if (!file_exists($path)) {
            $this->markTestSkipped("Built live-edit-app.js missing.");
        }
        $built = file_get_contents($path);

        // Functional pin per cycle-142 lesson — the renamed label
        // and auto-save logic MUST reach the compiled JS.
        $this->assertStringContainsString('Save & Publish', $built,
            'Built live-edit-app.js MUST contain "Save & Publish" '
            . '— confirms AI-271 relabel shipped to the bundle.');
        $this->assertStringContainsString('Save and publish page', $built,
            'Built live-edit-app.js MUST contain the new aria-label '
            . '"Save and publish page".');
        $this->assertStringContainsString('3e4', $built,
            'Built live-edit-app.js MUST contain the 30000ms interval '
            . '(minified as 3e4) — confirms AUTO_SAVE_INTERVAL_MS '
            . 'shipped.');
    }
}
