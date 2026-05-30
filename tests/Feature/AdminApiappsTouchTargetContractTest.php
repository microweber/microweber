<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-30-apiapps — Admin /api-applications hand-rolled Tailwind
 * input + CTA touch-target floor on mobile (390x844 probe).
 *
 * DEFECT (probed at 390x844 on /admin/api-applications)
 *
 *   4 interactive elements rendered under the 44x44 WCAG 2.5.5 floor:
 *     - 3 hand-rolled <input> elements carrying the cosmetic .fi-input
 *       class plus Tailwind `px-3 py-2 text-sm` — yields 40px height
 *       (4px short). Sites: newTokenName, newAppName, newAppRedirect.
 *     - 1 hand-rolled <a class="bg-primary-600 px-4 py-2 text-sm">
 *       "Open interactive API documentation" CTA at ~36px (8px short).
 *
 * FIX
 *   Inline style attribute injection WITH !important flag — same recipe
 *   as task-2026-05-30-tplcust + task-2026-05-30-mediabrowser. The 3
 *   inputs visually use the .fi-input class but they are NOT the
 *   Filament native form-component (they are bare <input> tags), so the
 *   global .fi-input height rule does not apply uniformly here.
 *
 *     - 3 inputs carry style="min-height:44px !important;"
 *     - 1 anchor CTA carries style="min-height:44px !important;"
 *
 * SCOPE NOTE
 *   Surgical inline-style fix chosen because (a) the file is a single
 *   per-page Blade with no shared template owner, (b) the elements are
 *   hand-rolled Tailwind not consumed by any other surface, (c) the
 *   global .fi-select-input fix in mobile-touch.css (task-2026-05-30-
 *   fiselect) deliberately stops at native Filament-component classes.
 */
class AdminApiappsTouchTargetContractTest extends TestCase
{
    private string $blade;
    private string $bladeStripped;

    protected function setUp(): void
    {
        parent::setUp();

        $this->blade = (string) file_get_contents(base_path(
            'src/MicroweberPackages/User/resources/views/filament/pages/api-applications.blade.php'
        ));
        // Strip Blade {{-- ... --}} + PHP block comments before negative
        // assertions so docblock prose mentioning attribute strings does not
        // satisfy the source-presence assertions (UNIFORMITY-RULE per
        // task-7aa48a).
        $stripped = (string) preg_replace('~\{\{--.*?--\}\}~s', '', $this->blade);
        $stripped = (string) preg_replace('~/\*.*?\*/~s', '', $stripped);
        $this->bladeStripped = $stripped;
    }

    #[Test]
    public function new_token_name_input_carries_inline_min_height_44(): void
    {
        $this->assertMatchesRegularExpression(
            '/wire:model="newTokenName"[\s\S]*?style="min-height:44px !important;"/',
            $this->bladeStripped,
            'newTokenName input must carry inline style="min-height:44px !important;".'
        );
    }

    #[Test]
    public function new_app_name_input_carries_inline_min_height_44(): void
    {
        $this->assertMatchesRegularExpression(
            '/wire:model="newAppName"[\s\S]*?style="min-height:44px !important;"/',
            $this->bladeStripped,
            'newAppName input must carry inline style="min-height:44px !important;".'
        );
    }

    #[Test]
    public function new_app_redirect_input_carries_inline_min_height_44(): void
    {
        $this->assertMatchesRegularExpression(
            '/wire:model="newAppRedirect"[\s\S]*?style="min-height:44px !important;"/',
            $this->bladeStripped,
            'newAppRedirect input must carry inline style="min-height:44px !important;".'
        );
    }

    #[Test]
    public function api_documentation_cta_carries_inline_min_height_44(): void
    {
        $this->assertMatchesRegularExpression(
            '/href="\{\{\s*url\(\'api\/documentation\'\)\s*\}\}"[\s\S]*?style="min-height:44px !important;"/',
            $this->bladeStripped,
            'API documentation CTA <a> must carry inline style="min-height:44px !important;".'
        );
    }

    #[Test]
    public function apiapps_task_marker_present_in_blade(): void
    {
        $this->assertStringContainsString('task-2026-05-30-apiapps', $this->blade);
    }

    #[Test]
    public function no_unstyled_hand_rolled_fi_input_remains(): void
    {
        // Defence: every hand-rolled <input class="fi-input ..."> on this
        // page MUST carry the inline 44px style. Walk each match and
        // assert. Slice-per-element scan avoids PCRE backtracking
        // ambiguity from a single greedy regex.
        if (!preg_match_all('~<input\b[^>]*class="fi-input\b[^"]*"[^>]*>~', $this->bladeStripped, $matches)) {
            $this->fail('Expected hand-rolled fi-input inputs in api-applications.blade.php (none found).');
        }
        $this->assertCount(3, $matches[0], 'Expected exactly 3 hand-rolled fi-input inputs (newTokenName + newAppName + newAppRedirect).');
        foreach ($matches[0] as $tag) {
            $this->assertStringContainsString(
                'style="min-height:44px !important;"',
                $tag,
                "Legacy unstyled hand-rolled fi-input shape detected — missing inline 44px !important style:\n{$tag}"
            );
        }
    }
}
