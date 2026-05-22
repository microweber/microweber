<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-22-7c7804 / AI-904 + AI-905 — Blog module settings wired to templates.
 *
 * AI-872 Slice 1 added Layout toggle (grid/list) and Show Categories / Show Tags
 * fields to BlogSettings.php, but never forwarded those values to the Blade
 * templates that render blog posts. Stage-1 data-shipped-consumer-not-wired defect.
 *
 * AI-904 — Layout toggle (col-md-6 vs col-12)
 *   BlogComponent now passes $layout to the view.
 *   default.blade.php computes $colClass from $layout:
 *     list  → 'col-12 mb-3'
 *     grid  → 'col-md-6 mb-5' (default)
 *
 * AI-905 — Show Categories / Show Tags toggles
 *   BlogComponent now passes $showCategories / $showTags to the view.
 *   post-card.blade.php gates the category and tag badge blocks:
 *     ($showCategories ?? true) && $post->categoryItems->count() > 0
 *     ($showTags      ?? true)  && $post->tagged->count()      > 0
 *
 * Style: file-system reads only, no DB / Livewire boot.
 */
class Blog7c7804AI904905SettingsWiredContractTest extends TestCase
{
    private const COMPONENT  = 'Modules/Blog/Livewire/BlogComponent.php';
    private const DEFAULT_BL = 'Modules/Blog/resources/views/livewire/blog/default.blade.php';
    private const CARD_BL    = 'Modules/Blog/resources/views/livewire/blog/post-card.blade.php';

    private string $component;
    private string $componentStripped;
    private string $defaultBlade;
    private string $defaultBladeStripped;
    private string $cardBlade;
    private string $cardBladeStripped;

    protected function setUp(): void
    {
        parent::setUp();

        $stripPhp  = fn (string $s): string => preg_replace('~/\*[\s\S]*?\*/~s', '', preg_replace('~//[^\n]*~', '', $s) ?? $s) ?? $s;
        $stripBlade = fn (string $s): string => preg_replace('~\{\{--[\s\S]*?--\}\}~s', '', $s) ?? $s;

        $rawComp = (string) file_get_contents(base_path(self::COMPONENT));
        $this->component = $rawComp;
        $this->componentStripped = $stripPhp($rawComp);

        $rawDefault = (string) file_get_contents(base_path(self::DEFAULT_BL));
        $this->defaultBlade = $rawDefault;
        $this->defaultBladeStripped = $stripBlade($rawDefault);

        $rawCard = (string) file_get_contents(base_path(self::CARD_BL));
        $this->cardBlade = $rawCard;
        $this->cardBladeStripped = $stripBlade($rawCard);
    }

    // ─── Task markers ─────────────────────────────────────────────────────────

    #[Test]
    public function task_marker_in_component(): void
    {
        $this->assertStringContainsString('task-2026-05-22-7c7804', $this->component,
            'BlogComponent.php must carry the AI-904/AI-905 task marker.');
    }

    #[Test]
    public function task_marker_in_default_blade(): void
    {
        $this->assertStringContainsString('task-2026-05-22-7c7804', $this->defaultBlade,
            'default.blade.php must carry the AI-904/AI-905 task marker.');
    }

    #[Test]
    public function task_marker_in_post_card_blade(): void
    {
        $this->assertStringContainsString('task-2026-05-22-7c7804', $this->cardBlade,
            'post-card.blade.php must carry the AI-904/AI-905 task marker.');
    }

    // ─── AI-904: BlogComponent passes layout to view ──────────────────────────

    #[Test]
    public function component_passes_layout_to_view(): void
    {
        $this->assertStringContainsString("'layout'", $this->componentStripped,
            'BlogComponent::render() must pass layout key to the view');
        $this->assertMatchesRegularExpression(
            '~[\'"]layout[\'"]\s*=>\s*\$this->layout~',
            $this->componentStripped,
            "view() array must map 'layout' => \$this->layout"
        );
    }

    #[Test]
    public function component_passes_show_categories_to_view(): void
    {
        $this->assertMatchesRegularExpression(
            '~[\'"]showCategories[\'"]\s*=>\s*\$this->showCategories~',
            $this->componentStripped,
            "view() array must map 'showCategories' => \$this->showCategories"
        );
    }

    #[Test]
    public function component_passes_show_tags_to_view(): void
    {
        $this->assertMatchesRegularExpression(
            '~[\'"]showTags[\'"]\s*=>\s*\$this->showTags~',
            $this->componentStripped,
            "view() array must map 'showTags' => \$this->showTags"
        );
    }

    // ─── AI-904: default.blade.php computes $colClass from $layout ────────────

    #[Test]
    public function default_blade_computes_col_class(): void
    {
        $this->assertStringContainsString('$colClass', $this->defaultBladeStripped,
            'default.blade.php must compute $colClass from $layout');
    }

    #[Test]
    public function default_blade_list_layout_gives_col_12(): void
    {
        $this->assertMatchesRegularExpression(
            '~=== [\'"]list[\'"][^\n]*[\'"](col-12[^\'"]*)~',
            $this->defaultBladeStripped,
            "list layout must map to 'col-12' column class"
        );
    }

    #[Test]
    public function default_blade_grid_layout_gives_col_md_6(): void
    {
        $this->assertStringContainsString('col-md-6', $this->defaultBladeStripped,
            'grid layout (default) must map to col-md-6 column class');
    }

    #[Test]
    public function default_blade_uses_col_class_variable_not_hardcoded(): void
    {
        // The per-post wrapper div must use $colClass, not a hardcoded col-md-6.
        $this->assertMatchesRegularExpression(
            '~<div\s+class="\{\{\s*\$colClass\s*\}\}"~',
            $this->defaultBladeStripped,
            'Post wrapper div must use {{ $colClass }} not a hardcoded col-md-6'
        );
    }

    #[Test]
    public function default_blade_no_longer_hardcodes_col_md_6_on_post_wrapper(): void
    {
        // After the fix, col-md-6 must NOT appear as a hardcoded literal in the
        // per-post div. Pre-strip Blade comments (selector-self-match guard).
        $this->assertDoesNotMatchRegularExpression(
            '~<div\s+class="col-md-6~',
            $this->defaultBladeStripped,
            'Post wrapper div must not hardcode col-md-6 — it must use $colClass'
        );
    }

    // ─── AI-905: post-card.blade.php gates categories and tags ────────────────

    #[Test]
    public function post_card_gates_categories_on_show_categories(): void
    {
        $this->assertMatchesRegularExpression(
            '~@if\s*\(\s*\(\s*\$showCategories\s*\?\?\s*true\s*\)~',
            $this->cardBladeStripped,
            'post-card.blade.php must gate category badges with ($showCategories ?? true)'
        );
    }

    #[Test]
    public function post_card_gates_tags_on_show_tags(): void
    {
        $this->assertMatchesRegularExpression(
            '~@if\s*\(\s*\(\s*\$showTags\s*\?\?\s*true\s*\)~',
            $this->cardBladeStripped,
            'post-card.blade.php must gate tag badges with ($showTags ?? true)'
        );
    }

    #[Test]
    public function post_card_categories_still_require_post_data(): void
    {
        // The AND condition with ->count() > 0 must still be present.
        $this->assertStringContainsString('categoryItems->count() > 0', $this->cardBladeStripped,
            'Category block must still require $post->categoryItems->count() > 0');
    }

    #[Test]
    public function post_card_tags_still_require_post_data(): void
    {
        $this->assertStringContainsString('tagged->count() > 0', $this->cardBladeStripped,
            'Tag block must still require $post->tagged->count() > 0');
    }

    // ─── AI-872 Slice 1 regression guard ─────────────────────────────────────

    #[Test]
    public function component_still_reads_layout_from_settings(): void
    {
        $this->assertStringContainsString("'layout']", $this->componentStripped,
            "BlogComponent mount() must still read \$settings['options']['layout']");
        $this->assertStringContainsString('$this->layout', $this->componentStripped,
            'BlogComponent must still assign $this->layout from settings');
    }

    #[Test]
    public function component_still_reads_show_categories_from_settings(): void
    {
        $this->assertStringContainsString("'show_categories']", $this->componentStripped,
            "BlogComponent mount() must still read \$settings['options']['show_categories']");
    }
}
