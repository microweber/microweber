<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-28-571193 — Layout settings handle menu icons disappeared
 * instantly on unhover instead of fading out.
 *
 * Root cause: `transition:.2s` was declared ONLY inside the `:hover` state
 * for `.mw-le-handle-menu-button ~ *` in handles.scss (desktop @media block).
 * CSS applies the DESTINATION state's transition property, not the source's.
 * On hover: destination = :hover, transition is present → smooth fade-IN.
 * On unhover: destination = base state, transition was absent → instant snap.
 *
 * Fix: move `transition: opacity .2s, height .2s` to the BASE state so the
 * exit animation uses it when transitioning back to opacity:0.
 *
 * Source-of-truth:
 *   packages/frontend-assets/resources/assets/css/scss/handles.scss
 *   (inside @media (min-width: 801px) #mw-handle-item-layout-root block)
 *
 * Selector-self-match guard: setUp() pre-strips CSS block comments.
 */
class LiveEdit571193HandleMenuIconFadeContractTest extends TestCase
{
    private const SCSS_SOURCE = 'packages/frontend-assets/resources/assets/css/scss/handles.scss';
    private const SERVED_CSS = 'public/vendor/microweber-packages/frontend-assets/build/liveedit.css';

    private string $scssSource;
    private string $scssStripped;
    private string $servedCss;

    protected function setUp(): void
    {
        parent::setUp();

        $this->scssSource = (string) file_get_contents(base_path(self::SCSS_SOURCE));
        $this->scssStripped = (string) preg_replace('~/\*[\s\S]*?\*/~', '', $this->scssSource);
        $this->servedCss = (string) file_get_contents(base_path(self::SERVED_CSS));
    }

    // -----------------------------------------------------------------------
    // Group A — SCSS source has transition in base state (not only :hover)
    // -----------------------------------------------------------------------

    #[Test]
    public function base_state_has_transition_property(): void
    {
        // The @media (min-width: 801px) block must define transition on the
        // base (non-hover) sibling-selector rule so unhover fades out.
        $this->assertMatchesRegularExpression(
            '/@media\s*\(\s*min-width\s*:\s*801px\s*\)[\s\S]{0,500}?\.mw-le-handle-menu-button ~ \*\s*\{[^}]*transition\s*:/m',
            $this->scssStripped,
            'task-571193: base state `.mw-le-handle-menu-button ~ *` inside the desktop @media block MUST carry a transition property so icons fade out on unhover.'
        );
    }

    #[Test]
    public function base_state_transition_includes_opacity(): void
    {
        $this->assertStringContainsString(
            'transition: opacity .2s',
            $this->scssSource,
            'task-571193: base state transition MUST include opacity .2s for the fade-out animation.'
        );
    }

    // -----------------------------------------------------------------------
    // Group B — Compiled/served CSS has transition on base selector
    // -----------------------------------------------------------------------

    #[Test]
    public function served_css_base_rule_has_transition(): void
    {
        // The first (base) rule for the sibling selector must contain transition.
        $this->assertMatchesRegularExpression(
            '/\.mw-le-handle-menu-button~\*\{height:0;opacity:0;transition:opacity \.2s/',
            $this->servedCss,
            'task-571193: compiled liveedit.css base state rule for `.mw-le-handle-menu-button~*` MUST include transition:opacity .2s so exit animations work.'
        );
    }

    #[Test]
    public function served_css_hover_rule_still_has_opacity_one(): void
    {
        $this->assertStringContainsString(
            ':hover .mw-le-handle-menu-button~*{height:auto;opacity:1;',
            $this->servedCss,
            'task-571193: hover rule for `.mw-le-handle-menu-button~*` MUST still set opacity:1 to show icons on hover.'
        );
    }

    // -----------------------------------------------------------------------
    // Group C — Regression guard: transition NOT only inside :hover in SCSS
    // -----------------------------------------------------------------------

    #[Test]
    public function transition_not_exclusively_in_hover_state(): void
    {
        // Before fix: transition was only present inside &:hover { ... }.
        // After fix: transition is in the base state too (checked in Group A).
        // Guard: the base state rule must NOT be missing transition while hover has it.
        $hasBaseTransition = (bool) preg_match(
            '/@media\s*\(\s*min-width\s*:\s*801px\s*\)[\s\S]{0,700}?\.mw-le-handle-menu-button ~ \*\s*\{[^}]*transition\s*:/m',
            $this->scssStripped
        );
        $this->assertTrue(
            $hasBaseTransition,
            'task-571193 regression guard: transition MUST be present in the BASE state rule inside the desktop @media block (not only in &:hover).'
        );
    }

    // -----------------------------------------------------------------------
    // Group D — Task-id markers present
    // -----------------------------------------------------------------------

    #[Test]
    public function task_id_marker_present_in_source(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-28-571193',
            $this->scssSource,
            'task-571193: SCSS source MUST carry the task-id marker for cross-surface audit grep.'
        );
    }
}
