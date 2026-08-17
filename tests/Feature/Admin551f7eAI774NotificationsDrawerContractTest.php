<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-551f7e / AI-774 — admin topbar notifications drawer.
 * Jira: https://microweber.atlassian.net/browse/AI-774
 *
 * Designer's Round-9 audit caught three defects on the Filament stock
 * `<x-filament-actions::database-notifications>` drawer empty state:
 *
 *   1. No header title — vendor swaps `Alignment::Center` and DROPS
 *      the header slot when $hasNotifications is false; drawer chrome
 *      "Notifications" label disappears.
 *   2. ~50% viewport-wide empty state — centered alignment + width="md"
 *      makes the empty drawer read as a floating sheet rather than the
 *      slide-over chrome the filled state uses. Inconsistent.
 *   3. Passive copy "Please check again later." — offloads next-step
 *      responsibility to the user.
 *
 * Fix is three-surface:
 *
 *   A. View override at
 *      resources/views/vendor/filament-notifications/database-notifications.blade.php
 *      drops the Alignment::Center swap (always slide-over), always
 *      renders the header slot with "Notifications" title +
 *      unread-count badge, and renders the empty-state body inside
 *      the same slide-over chrome.
 *
 *   B. Translation override at
 *      resources/lang/vendor/filament-notifications/en/database.php
 *      replaces empty heading "No notifications" → "All caught up"
 *      and empty description "Please check again later." →
 *      "You'll see new comments, orders, and messages here when they
 *      arrive." — action-aware, matches the AI-705 dashboard pattern.
 *
 *   C. CSS in general-styles.css scopes `.mw-no-database-empty`
 *      flex-column container + icon/heading/description typography
 *      from ESE tokens. Dark-theme aware via `.dark` parent.
 *      Token fallbacks belt-and-braces per SOUL #108.
 */
class Admin551f7eAI774NotificationsDrawerContractTest extends TestCase
{
    private string $blade;
    private string $lang;
    private string $css;
    private string $bundle;

    protected function setUp(): void
    {
        parent::setUp();
        $this->blade = (string) file_get_contents(base_path(
            'resources/views/vendor/filament-notifications/database-notifications.blade.php'
        ));
        $this->lang = (string) file_get_contents(base_path(
            'resources/lang/vendor/filament-notifications/en/database.php'
        ));
        $this->css = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css'
        ));
        $this->bundle = file_exists(base_path(
            'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css'
        )) ? (string) file_get_contents(base_path(
            'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css'
        )) : '';
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — view override always renders slide-over + header
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function view_override_drops_alignment_center_swap(): void
    {
        // Vendor swapped to Alignment::Center on empty; the override
        // must NOT carry that swap so the slide-over chrome is
        // consistent in both states.
        $this->assertStringNotContainsString(
            ':alignment="$hasNotifications ? null : Alignment::Center"',
            $this->blade,
            'View override must drop the vendor Alignment::Center swap so empty + filled both render as slide-over.'
        );
        $this->assertStringNotContainsString(
            'use Filament\Support\Enums\Alignment;',
            $this->blade,
            'View override should not need to import Alignment after dropping the swap.'
        );
    }

    #[Test]
    public function view_override_always_renders_header_slot(): void
    {
        // Strip Blade `{{-- … --}}` comments before scanning, because
        // the docblock at the top mentions vendor mechanics including
        // `@if ($hasNotifications)` as an explanation of what was
        // REMOVED (selector-self-match guard, recurring pattern).
        $stripped = preg_replace('/\{\{--.*?--\}\}/s', '', $this->blade);

        // Header slot must be rendered unconditionally — NOT wrapped
        // in `@if ($hasNotifications)`. Slice the header slot and
        // confirm no enclosing `@if` walking backwards.
        $slotStart = strpos($stripped, "<x-slot name=\"header\">");
        $this->assertNotFalse($slotStart);
        $sliceBefore = substr($stripped, max(0, $slotStart - 200), 200);
        $this->assertStringNotContainsString(
            '@if ($hasNotifications)',
            $sliceBefore,
            'Header slot must NOT be wrapped in `@if ($hasNotifications)` — the AI-774 fix requires the header to render in both empty and filled states.'
        );
    }

    #[Test]
    public function view_override_renders_empty_state_body_with_mw_class(): void
    {
        // The empty-state body must use `.mw-no-database-empty` so
        // the companion CSS rule scopes correctly.
        $this->assertStringContainsString(
            'class="mw-no-database-empty"',
            $this->blade,
            'View override must render the empty-state body with `.mw-no-database-empty` wrapper class.'
        );
        $this->assertStringContainsString(
            'mw-no-database-empty__icon',
            $this->blade,
            'Empty-state icon must carry the `mw-no-database-empty__icon` class.'
        );
        $this->assertStringContainsString(
            'mw-no-database-empty__heading',
            $this->blade,
            'Empty-state heading must carry the `mw-no-database-empty__heading` class.'
        );
        $this->assertStringContainsString(
            'mw-no-database-empty__description',
            $this->blade,
            'Empty-state description must carry the `mw-no-database-empty__description` class.'
        );
    }

    #[Test]
    public function view_override_keeps_clear_and_mark_all_actions_hidden_when_empty(): void
    {
        // The mark-all and clear actions render only when filled —
        // preserved vendor behaviour. Pattern: `@if ($hasNotifications && ...->isVisible())`.
        $this->assertStringContainsString(
            '@if ($hasNotifications && $unreadNotificationsCount && $this->markAllNotificationsAsReadAction?->isVisible())',
            $this->blade,
            'Mark-all-as-read action must remain gated on $hasNotifications.'
        );
        $this->assertStringContainsString(
            '@if ($hasNotifications && $this->clearNotificationsAction?->isVisible())',
            $this->blade,
            'Clear-notifications action must remain gated on $hasNotifications.'
        );
    }

    #[Test]
    public function view_override_preserves_broadcast_and_pagination(): void
    {
        // Vendor mechanics — broadcast channel listener, pagination
        // footer — must remain so live-updates + pagination continue
        // to work in the filled state.
        $this->assertStringContainsString('EchoLoaded', $this->blade, 'Broadcast Echo listener must remain.');
        $this->assertStringContainsString(
            '<x-filament::pagination :paginator="$notifications" />',
            $this->blade,
            'Pagination footer must remain.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — translation override replaces passive copy
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function translation_override_replaces_empty_heading_and_description(): void
    {
        // Empty heading: "No notifications" → "All caught up"
        $this->assertStringContainsString("'heading' => 'All caught up'", $this->lang);
        // Empty description: "Please check again later." → action-aware
        $this->assertStringContainsString(
            "'description' => \"You'll see new comments, orders, and messages here when they arrive.\"",
            $this->lang,
            'Translation override must replace passive description with action-aware copy.'
        );
        // The passive vendor copy must NOT appear in the override.
        $this->assertStringNotContainsString(
            "Please check again later.",
            preg_replace('!/\*.*?\*/!s', '', $this->lang),
            'Passive copy "Please check again later." must not appear in the AI-774 translation override (strip docblock first for selector-self-match guard).'
        );
    }

    #[Test]
    public function translation_override_preserves_vendor_action_keys(): void
    {
        // Non-empty translations pass through verbatim so the
        // override doesn't accidentally drop vendor strings.
        $this->assertStringContainsString("'clear' => [", $this->lang);
        $this->assertStringContainsString("'mark_all_as_read' => [", $this->lang);
        $this->assertStringContainsString("'heading' => 'Notifications'", $this->lang);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — CSS empty-state container + tokens
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function css_empty_state_container_is_flex_column(): void
    {
        $start = strpos($this->css, '.mw-no-database-empty {');
        $this->assertNotFalse($start);
        $end = strpos($this->css, '}', $start);
        $body = substr($this->css, $start, $end - $start);
        $this->assertStringContainsString('flex-direction: column', $body);
        $this->assertStringContainsString('text-align: center', $body);
        $this->assertStringContainsString('min-height: 320px', $body);
    }

    #[Test]
    public function css_token_fallbacks_present_on_every_var_in_slice(): void
    {
        // SOUL #108 — every var() in the AI-774 slice must carry a
        // literal fallback. Slice from the AI-774 docblock-end to the
        // next AI-block marker / EOF.
        $start = strpos($this->css, 'AI-774 (task-2026-05-17-551f7e)');
        $this->assertNotFalse($start);
        $docEnd = strpos($this->css, '*/', $start);
        $this->assertNotFalse($docEnd);
        $sliceStart = $docEnd + 2;
        $sliceEnd = strpos($this->css, "/*", $sliceStart);
        $slice = $sliceEnd === false
            ? substr($this->css, $sliceStart)
            : substr($this->css, $sliceStart, $sliceEnd - $sliceStart);
        preg_match_all('/var\(([^)]+)\)/', $slice, $matches);
        foreach ($matches[1] as $varExpr) {
            $this->assertStringContainsString(
                ',',
                $varExpr,
                "Every var() in the AI-774 CSS slice must carry a literal fallback. Offender: `var({$varExpr})`."
            );
        }
        $this->assertGreaterThan(0, count($matches[1]), 'AI-774 slice must consume ESE tokens.');
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — runtime probe + markers
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function bundle_carries_empty_state_class(): void
    {
        if ($this->bundle === '') {
            $this->markTestSkipped('Served microweber-filament-theme.css absent — run `cd packages/microweber-filament-theme && npm run build`.');
        }
        $this->assertStringContainsString(
            '.mw-no-database-empty',
            $this->bundle,
            'Served theme bundle must carry the AI-774 empty-state class. If absent, run the Webpack rebuild.'
        );
    }

    #[Test]
    public function task_id_and_ai774_markers_present(): void
    {
        $this->assertStringContainsString('task-2026-05-17-551f7e', $this->blade);
        $this->assertStringContainsString('AI-774', $this->blade);
        $this->assertStringContainsString('task-2026-05-17-551f7e', $this->lang);
        $this->assertStringContainsString('AI-774', $this->lang);
        $this->assertStringContainsString('task-2026-05-17-551f7e', $this->css);
        $this->assertStringContainsString('AI-774', $this->css);
    }
}
