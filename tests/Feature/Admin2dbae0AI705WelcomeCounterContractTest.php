<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-2dbae0 / AI-705 (Medium) — Welcome sub-line:
 * actionable counter, drop "Here's what's happening" filler.
 *
 * Designer dispatch (admin-shell-improvements-2026-05-16.md §2 AD4,
 * per-ticket email 2026-05-16T13:39): current dashboard welcome
 * reads `Welcome back, {Name} / Here's what's happening` — the
 * sub-line says nothing. v2 inherited the same filler.
 *
 * Fix:
 *   - Headline `Welcome back, {Name}` unchanged.
 *   - Sub-line becomes an actionable counter strip:
 *     `3 new comments · 1 new order · 0 new messages` — each
 *     segment is a clickable link routing to the relevant list.
 *   - `·` separator with `var(--space-xs)` either side.
 *   - Empty state: `All caught up.` (slightly heavier than
 *     --font-control) when all counters are zero.
 *   - Counter values come from the SAME backend queries as the
 *     matching Filament navigation badge (single source of truth).
 *
 * Three-surface implementation:
 *
 *   1. WelcomeWidget.php
 *      - getSubtitle() removed (no longer needed; the static
 *        "Here's what's happening" filler is gone).
 *      - New getCounters() returns an array of
 *        {count, label_singular, label_plural, url} entries —
 *        one per active Module (Comments / Order / Form). Each
 *        counter queries the SAME columns as the matching
 *        Filament navigation badge.
 *      - class_exists() guards make the widget tolerant of
 *        missing modules; safeResourceUrl() degrades gracefully
 *        to `#` when Filament context isn't fully booted.
 *      - isAllCaughtUp() returns true when every counter is 0
 *        AND at least one counter exists (avoids the misleading
 *        "All caught up." with zero modules installed).
 *
 *   2. welcome-widget.blade.php
 *      - <p class="mw-welcome-widget-subtitle"> replaced with the
 *        new <p class="mw-welcome-widget-counters"> + empty-state
 *        <p class="mw-welcome-widget-empty"> branch.
 *      - Each counter link carries aria-label including the count
 *        and pluralised label.
 *      - `·` separators carry aria-hidden="true" so AT users hear
 *        the counters as a flat list.
 *
 *   3. general-styles.css
 *      - .mw-welcome-widget-counters: inline-flex strip,
 *        --font-control size, --ese-text-muted metadata colour.
 *      - .mw-welcome-widget-counter: default --ese-text, hover
 *        shifts to --ese-accent + underline, focus-visible 2px
 *        --ese-accent outline.
 *      - .mw-welcome-widget-counter-sep: margin-inline var(--space-xs)
 *        per spec.
 *      - .mw-welcome-widget-empty: var(--font-section) + weight 500.
 *      - prefers-reduced-motion: reduce disables transition.
 */
class Admin2dbae0AI705WelcomeCounterContractTest extends TestCase
{
    private string $widgetClass;
    private string $widgetBlade;
    private string $generalStyles;

    protected function setUp(): void
    {
        parent::setUp();
        $this->widgetClass = (string) file_get_contents(base_path(
            'app/Filament/Admin/Widgets/WelcomeWidget.php'
        ));
        $this->widgetBlade = (string) file_get_contents(base_path(
            'resources/views/filament/admin/widgets/welcome-widget.blade.php'
        ));
        $this->generalStyles = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — Widget PHP shape
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function welcome_widget_no_longer_returns_filler_subtitle(): void
    {
        // The "Here's what's happening" filler string MUST be gone
        // from the widget class entirely — designer rejected it as
        // empty filler.
        $this->assertStringNotContainsString(
            "Here's what's happening",
            $this->widgetClass,
            "The static filler subtitle \"Here's what's happening\" must be removed from WelcomeWidget per spec §2 AD4."
        );
    }

    #[Test]
    public function welcome_widget_keeps_greeting_method(): void
    {
        $this->assertMatchesRegularExpression(
            '/public\s+function\s+getGreeting\(\)\s*:\s*string/',
            $this->widgetClass,
            'getGreeting() must remain — only the sub-line changes per spec.'
        );
        $this->assertStringContainsString(
            'Welcome back',
            $this->widgetClass,
            'The "Welcome back, {Name}" headline copy must remain unchanged.'
        );
    }

    #[Test]
    public function welcome_widget_exposes_counters_method(): void
    {
        $this->assertMatchesRegularExpression(
            '/public\s+function\s+getCounters\(\)\s*:\s*array/',
            $this->widgetClass,
            'getCounters() must be defined as the public array source for the Blade view.'
        );
    }

    #[Test]
    public function welcome_widget_exposes_all_caught_up_helper(): void
    {
        $this->assertMatchesRegularExpression(
            '/public\s+function\s+isAllCaughtUp\(\)\s*:\s*bool/',
            $this->widgetClass,
            'isAllCaughtUp() must be defined so the Blade view can switch to the empty-state copy.'
        );
    }

    #[Test]
    public function counters_use_filament_nav_badge_query_shapes(): void
    {
        // SAME query columns as the matching nav-badge methods —
        // pinning these in the contract keeps the two surfaces in
        // sync. (Bell badge cannot show "3 new comments" while the
        // welcome widget says zero.)
        $this->assertMatchesRegularExpression(
            "/is_moderated[\\s\\S]{0,200}false[\\s\\S]{0,200}is_spam[\\s\\S]{0,200}false/",
            $this->widgetClass,
            "Comments counter must query is_moderated=false AND is_spam=false (matches CommentResource::getNavigationBadge query)."
        );
        $this->assertMatchesRegularExpression(
            "/order_status[\\s\\S]{0,200}OrderStatus::New/",
            $this->widgetClass,
            'Orders counter must query order_status = OrderStatus::New (matches OrderResource::getNavigationBadge query).'
        );
        $this->assertMatchesRegularExpression(
            "/is_read[\\s\\S]{0,80}0/",
            $this->widgetClass,
            'Messages counter must query is_read = 0 on FormData (visitor-inbox unread shape).'
        );
    }

    #[Test]
    public function counters_use_class_exists_guards_for_missing_modules(): void
    {
        // The widget MUST survive a Modules/<Comments|Order|Form>
        // missing checkout — class_exists() guard is the canonical
        // pattern.
        $this->assertMatchesRegularExpression(
            '/class_exists\(\s*\\\\Modules\\\\Comments\\\\Models\\\\Comment::class\s*\)/',
            $this->widgetClass,
            'Comments counter must be class_exists()-guarded so a missing module never breaks the widget.'
        );
        $this->assertMatchesRegularExpression(
            '/class_exists\(\s*\\\\Modules\\\\Order\\\\Models\\\\Order::class\s*\)/',
            $this->widgetClass,
            'Orders counter must be class_exists()-guarded.'
        );
        $this->assertMatchesRegularExpression(
            '/class_exists\(\s*\\\\Modules\\\\Form\\\\Models\\\\FormData::class\s*\)/',
            $this->widgetClass,
            'Messages counter must be class_exists()-guarded.'
        );
    }

    #[Test]
    public function counters_resolve_urls_via_safe_resource_url_helper(): void
    {
        // Routes are encapsulated behind ResourceClass::getUrl('index')
        // rather than route-name strings — keeps slug renames internal
        // to each Module.
        $this->assertMatchesRegularExpression(
            "/private\\s+function\\s+safeResourceUrl\\s*\\(\\s*string\\s+\\\$resourceClass\\s*\\)\\s*:\\s*string/",
            $this->widgetClass,
            'safeResourceUrl() helper must wrap Resource::getUrl(\'index\') so Filament boot failures degrade to a harmless # href.'
        );
        $this->assertStringContainsString(
            "\$resourceClass::getUrl('index')",
            $this->widgetClass,
            'safeResourceUrl() must call $resourceClass::getUrl(\'index\') inside its try/catch.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — Blade view shape
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function blade_no_longer_renders_static_subtitle(): void
    {
        // The pre-AI-705 <p class="mw-welcome-widget-subtitle">
        // bound to getSubtitle() MUST be gone.
        $this->assertStringNotContainsString(
            'getSubtitle()',
            $this->widgetBlade,
            'Blade must no longer call $this->getSubtitle() — the static filler is gone per spec §2 AD4.'
        );
        $this->assertStringNotContainsString(
            'mw-welcome-widget-subtitle',
            $this->widgetBlade,
            'The legacy `.mw-welcome-widget-subtitle` class must be removed from the Blade.'
        );
    }

    #[Test]
    public function blade_renders_counter_strip_or_empty_state(): void
    {
        // The new shape is: if isAllCaughtUp() → empty state;
        // elseif counters → counter strip. Pin both branches.
        $this->assertMatchesRegularExpression(
            '/@if\s*\(\s*\$this->isAllCaughtUp\(\)\s*\)[\s\S]*?mw-welcome-widget-empty[\s\S]*?@elseif\s*\(\s*!\s*empty\(\$counters\)\s*\)[\s\S]*?mw-welcome-widget-counters/',
            $this->widgetBlade,
            'Blade must branch: @if isAllCaughtUp → mw-welcome-widget-empty; @elseif counters → mw-welcome-widget-counters.'
        );
    }

    #[Test]
    public function blade_loops_counters_with_aria_hidden_separator(): void
    {
        $this->assertMatchesRegularExpression(
            '/@foreach\s*\(\s*\$counters\s+as\s+\$i\s*=>\s*\$counter\s*\)[\s\S]*?mw-welcome-widget-counter-sep[\s\S]*?aria-hidden="true"[\s\S]*?·/',
            $this->widgetBlade,
            'Blade must @foreach counters with an aria-hidden=true `·` separator between segments.'
        );
    }

    #[Test]
    public function blade_renders_each_counter_as_anchor_with_aria_label(): void
    {
        $this->assertMatchesRegularExpression(
            '/<a\s+href="\{\{\s*\$counter\[\'url\'\]\s*\}\}"[\s\S]*?class="mw-welcome-widget-counter"[\s\S]*?aria-label=/',
            $this->widgetBlade,
            'Each counter must render as <a href=$counter[url] class=mw-welcome-widget-counter aria-label=...> so screen-reader output is meaningful.'
        );
    }

    #[Test]
    public function blade_pluralises_label_per_count(): void
    {
        // count === 1 → label_singular, else label_plural.
        $this->assertMatchesRegularExpression(
            "/\\\$counter\\['count'\\]\\s*===\\s*1\\s*\\?\\s*\\\$counter\\['label_singular'\\]\\s*:\\s*\\\$counter\\['label_plural'\\]/",
            $this->widgetBlade,
            'Blade must pluralise the label per the segment count (count === 1 ? singular : plural).'
        );
    }

    #[Test]
    public function blade_empty_state_says_all_caught_up(): void
    {
        $this->assertMatchesRegularExpression(
            '/class="mw-welcome-widget-empty"[^>]*>\s*All caught up\.\s*<\/p>/',
            $this->widgetBlade,
            'Empty-state copy must be exactly "All caught up." per designer spec.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — CSS shape
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function counters_css_uses_inline_flex_and_font_control(): void
    {
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.mw-welcome-widget-counters\s*\{[^}]*display:\s*inline-flex[^}]*font-size:\s*var\(--font-control/s',
            $this->generalStyles,
            '.mw-welcome-widget-counters must be inline-flex with font-size: var(--font-control).'
        );
    }

    #[Test]
    public function counter_separator_uses_space_xs_margin(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-welcome-widget-counter-sep\s*\{[^}]*margin-inline:\s*var\(--space-xs,\s*6px\)/s',
            $this->generalStyles,
            'The `·` separator must use margin-inline: var(--space-xs, 6px) per spec (xs on both sides).'
        );
    }

    #[Test]
    public function counter_anchor_hover_uses_accent_underline(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-welcome-widget-counter:hover\s*\{[^}]*color:\s*var\(--ese-accent[^}]*text-decoration:\s*underline/s',
            $this->generalStyles,
            'Counter anchors must shift to --ese-accent + underline on :hover.'
        );
    }

    #[Test]
    public function counter_anchor_focus_visible_uses_accent_outline(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-welcome-widget-counter:focus-visible\s*\{[^}]*outline:\s*2px\s+solid\s+var\(--ese-accent/s',
            $this->generalStyles,
            'Counter anchors must carry a 2px --ese-accent focus-visible outline (a11y).'
        );
    }

    #[Test]
    public function empty_state_is_heavier_than_font_control(): void
    {
        // Spec: "short, satisfying, slightly larger weight than
        // --font-control". Implemented via --font-section + 500 weight.
        $this->assertMatchesRegularExpression(
            '/\.mw-welcome-widget-empty\s*\{[^}]*font-size:\s*var\(--font-section[^}]*font-weight:\s*500/s',
            $this->generalStyles,
            '.mw-welcome-widget-empty must use --font-section (15px) + font-weight:500 to read heavier than counter metadata.'
        );
    }

    #[Test]
    public function reduced_motion_disables_counter_transition(): void
    {
        $this->assertMatchesRegularExpression(
            '/@media\s*\(\s*prefers-reduced-motion:\s*reduce\s*\)\s*\{[\s\S]*?\.mw-welcome-widget-counter\s*\{[^}]*transition:\s*none/s',
            $this->generalStyles,
            'prefers-reduced-motion: reduce must disable the counter anchor colour transition.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — Markers + token-fallback hygiene
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_marker_present_in_all_three_files(): void
    {
        $this->assertStringContainsString('task-2026-05-16-2dbae0', $this->widgetClass);
        $this->assertStringContainsString('task-2026-05-16-2dbae0', $this->widgetBlade);
        $this->assertStringContainsString('task-2026-05-16-2dbae0', $this->generalStyles);
    }

    #[Test]
    public function css_tokens_carry_literal_fallbacks(): void
    {
        $start = strpos($this->generalStyles, 'AI-705 — Welcome widget actionable counter');
        $this->assertNotFalse($start, 'AI-705 task marker must be present in general-styles.css.');
        $slice = substr($this->generalStyles, $start);
        $tokens = [
            '--font-control'   => '13px',
            '--font-section'   => '15px',
            '--space-xs'       => '6px',
            '--radius-sm'      => '6px',
            '--ese-text'       => '#111827',
            '--ese-text-muted' => '#6b7280',
            '--ese-accent'     => '#0d6efd',
            '--t-fast'         => '120ms',
        ];
        foreach ($tokens as $token => $fallback) {
            $this->assertMatchesRegularExpression(
                '/var\(' . preg_quote($token, '/') . ',\s*[^)]*' . preg_quote($fallback, '/') . '/',
                $slice,
                "Token {$token} must be consumed as var({$token}, <literal {$fallback}>) in the AI-705 slice."
            );
        }
    }
}
