<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-28-0d7e2a / AI-1226
 * Live-edit Layers panel Configure buttons: 24-32px → 44px touch target.
 *
 * Root cause:
 *  KPI-1 — `.module-settings-button` was `height: 32px; width: 32px` — below
 *  the WCAG 2.5.5 44px floor. The "Insert content into layout" add-module-button
 *  was 24px (SVG-natural height, no explicit container size).
 *
 *  Affected buttons (visible only when a canvas element is selected):
 *    Insert content into layout, Configure Social Links, Configure Button,
 *    Configure Shop/Cart, Configure Logo, Configure Menu.
 *
 * Fix:
 *  - `.module-settings-button`: `height/width: 32px` → `min-height/min-width: 44px`
 *  - `.add-module-button`: explicit `min-height: 44px; min-width: 44px` added
 *  - SVG/img inside `.module-settings-button`: 16px → 20px (visual proportion)
 *
 * Source-of-truth:
 *   packages/frontend-assets/resources/assets/ui/components/ContextMenu/CurrentLayoutSettingsButtons.vue
 */
class LiveEdit0d7e2aAI1226LayersConfigureButtonsTouchTargetContractTest extends TestCase
{
    private const VUE_SOURCE = 'packages/frontend-assets/resources/assets/ui/components/ContextMenu/CurrentLayoutSettingsButtons.vue';

    private string $vueSource;
    private string $vueStripped;

    protected function setUp(): void
    {
        parent::setUp();

        $this->vueSource   = (string) file_get_contents(base_path(self::VUE_SOURCE));
        $stripped = (string) preg_replace('~/\*[\s\S]*?\*/~', '', $this->vueSource);
        $this->vueStripped = (string) preg_replace('~//[^\n]*~', '', $stripped);
    }

    // -----------------------------------------------------------------------
    // Group A — Configure buttons touch target (WCAG 2.5.5)
    // -----------------------------------------------------------------------

    #[Test]
    public function module_settings_button_has_min_height_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.module-settings-button\s*\{[^}]*min-height\s*:\s*44px/s',
            $this->vueSource,
            'AI-1226: .module-settings-button MUST have min-height: 44px (WCAG 2.5.5 44px floor).'
        );
    }

    #[Test]
    public function module_settings_button_has_min_width_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.module-settings-button\s*\{[^}]*min-width\s*:\s*44px/s',
            $this->vueSource,
            'AI-1226: .module-settings-button MUST have min-width: 44px (WCAG 2.5.5 44px floor).'
        );
    }

    #[Test]
    public function module_settings_button_no_longer_has_fixed_32px_height(): void
    {
        $this->assertDoesNotMatchRegularExpression(
            '/\.module-settings-button\s*\{[^}]*\bheight\s*:\s*32px/s',
            $this->vueStripped,
            'AI-1226: .module-settings-button MUST NOT carry fixed `height: 32px` — that was the 12px-short pre-fix value.'
        );
    }

    #[Test]
    public function module_settings_button_no_longer_has_fixed_32px_width(): void
    {
        $this->assertDoesNotMatchRegularExpression(
            '/\.module-settings-button\s*\{[^}]*\bwidth\s*:\s*32px/s',
            $this->vueStripped,
            'AI-1226: .module-settings-button MUST NOT carry fixed `width: 32px` — that was the pre-fix value.'
        );
    }

    // -----------------------------------------------------------------------
    // Group B — Insert content button touch target (WCAG 2.5.5)
    // -----------------------------------------------------------------------

    #[Test]
    public function add_module_button_has_min_height_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.add-module-button\s*\{[^}]*min-height\s*:\s*44px/s',
            $this->vueSource,
            'AI-1226: .add-module-button MUST have min-height: 44px (Insert content button was 24px SVG-natural).'
        );
    }

    #[Test]
    public function add_module_button_has_min_width_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.add-module-button\s*\{[^}]*min-width\s*:\s*44px/s',
            $this->vueSource,
            'AI-1226: .add-module-button MUST have min-width: 44px.'
        );
    }

    // -----------------------------------------------------------------------
    // Group C — SVG icon size proportional to 44px container
    // -----------------------------------------------------------------------

    #[Test]
    public function module_settings_button_svg_is_20px(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.module-settings-button :deep\(svg\)\s*\{[^}]*width\s*:\s*20px/s',
            $this->vueSource,
            'AI-1226: .module-settings-button :deep(svg) MUST be 20px (proportional to 44px container).'
        );
    }

    #[Test]
    public function module_settings_button_img_is_20px(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.module-settings-button :deep\(img\)\s*\{[^}]*width\s*:\s*20px/s',
            $this->vueSource,
            'AI-1226: .module-settings-button :deep(img) MUST be 20px.'
        );
    }

    // -----------------------------------------------------------------------
    // Group D — task-id markers
    // -----------------------------------------------------------------------

    #[Test]
    public function task_id_marker_in_source(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-28-0d7e2a',
            $this->vueSource,
            'AI-1226: Vue source MUST carry the task-id marker for cross-surface audit grep.'
        );
    }
}
