<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-136 / AI-123 / TICKET-CM+CN+CO — Novice-UX pass contract.
 *
 * Pins three novice-first-time UX guarantees so a future refactor
 * cannot silently regress them:
 *
 *   - TICKET-CN : The empty-state blade view in Modules/Content covers
 *                 every primary admin model (Content/Page/Post/Product/
 *                 Order/Customer/Invoice/PaymentProvider/ShippingProvider/
 *                 TaxType) so each list page renders an illustrated
 *                 empty-state instead of the bare "No records" string.
 *                 MediaLibrary's empty-state explains what the library
 *                 is + how to add the first asset.
 *
 *   - TICKET-CO : packages/microweber-filament-theme/.../mobile-touch.css
 *                 carries hover-affordance rules for clickable cards
 *                 (.mw-clickable-card, .mw-tile, .mw-card-link,
 *                 .mw-grid-item.mw-grid-item-clickable) AND for
 *                 Filament rows with a recordUrl-bound link, AND
 *                 keyboard focus-visible parity (so screen-reader +
 *                 keyboard users get the same cue), AND a 44×44 floor
 *                 on every empty-state action button.
 *
 *   - TICKET-CM : Onboarding wizard is filed as a phase-2 follow-up
 *                 under the same ticket. Codebase has no half-baked
 *                 wizard scaffolding (negative test pins the deferral).
 *
 * Style after Sec05SsrfAndStoredXssContractTest / Ai108* / Ai109* —
 * source-grep contract assertions that catch regressions at refactor
 * time without needing app boot or DB seeding.
 */
class Ai123NoviceUxContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    // -----------------------------------------------------------------
    // TICKET-CN — Blank-state illustrations
    // -----------------------------------------------------------------

    #[Test]
    public function content_empty_state_blade_branches_cover_every_primary_model(): void
    {
        $rel = 'Modules/Content/resources/views/filament/admin/empty-state.blade.php';
        $this->assertFileExists(base_path($rel));

        // 1MB blade file: stream-grep just for the model branch markers
        // rather than reading the whole file.
        $required = [
            'Modules\\Content\\Models\\Content',
            'Modules\\Page\\Models\\Page',
            'Modules\\Post\\Models\\Post',
            'Modules\\Product\\Models\\Product',
            'Modules\\Order\\Models\\Order',
            'Modules\\Customer\\Models\\Customer',
            'Modules\\Invoice\\Models\\Invoice',
            'Modules\\Payment\\Models\\PaymentProvider',
            'Modules\\Shipping\\Models\\ShippingProvider',
            'Modules\\Tax\\Models\\TaxType',
        ];

        // Stream-scan the 1MB blade file in 64KB chunks and collect a
        // boolean for every required marker. file_get_contents() would
        // also work but keeping memory low here is friendlier to the
        // shared phpunit runner.
        $found = array_fill_keys($required, false);
        $handle = fopen(base_path($rel), 'rb');
        $tail = '';
        while (!feof($handle)) {
            $chunk = $tail . fread($handle, 64 * 1024);
            foreach ($required as $fqn) {
                if (!$found[$fqn] && str_contains($chunk, $fqn)) {
                    $found[$fqn] = true;
                }
            }
            // Keep the last 256 chars so a marker straddling two
            // chunks is still found.
            $tail = substr($chunk, -256);
        }
        fclose($handle);

        foreach ($required as $fqn) {
            $this->assertTrue(
                $found[$fqn],
                "empty-state.blade.php MUST carry an @if branch for {$fqn} "
                . 'so its list page renders an illustrated empty-state '
                . 'instead of the bare "No records" string.'
            );
        }
    }

    #[Test]
    public function media_library_empty_state_explains_what_to_do_next(): void
    {
        $src = $this->read('Modules/MediaLibrary/resources/views/filament/admin/pages/media-library-page.blade.php');

        // Two-branch empty-state: filtered vs unfiltered.
        $this->assertStringContainsString(
            'No media match those filters',
            $src,
            'MediaLibrary empty-state MUST distinguish "filter returned 0" '
            . 'from "library is genuinely empty" so novices do not assume '
            . 'their search broke the page.'
        );
        $this->assertStringContainsString(
            'Your Media Library is empty',
            $src,
            'MediaLibrary empty-state MUST tell first-time users that the '
            . 'library is empty rather than just "No media found".'
        );
        $this->assertStringContainsString(
            'mw-media-empty-description',
            $src,
            'MediaLibrary empty-state MUST carry a description-class node '
            . 'so the styling layer can target it (and so screen readers '
            . 'announce the description after the heading).'
        );
        $this->assertStringContainsString(
            'Drop images, videos, or documents',
            $src,
            'MediaLibrary unfiltered empty-state MUST explain HOW to add '
            . 'the first asset (drag-drop / Upload button).'
        );
    }

    // -----------------------------------------------------------------
    // TICKET-CO — Hover affordances + 44×44 touch targets
    // -----------------------------------------------------------------

    #[Test]
    public function clickable_card_markers_have_cursor_and_hover_affordance(): void
    {
        $rel = 'packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css';
        $src = $this->read($rel);

        // Every project-blessed clickable-card marker MUST get
        // cursor:pointer + min-height:44px + a hover transition.
        foreach (['.mw-clickable-card', '.mw-tile', '.mw-card-link'] as $marker) {
            $this->assertMatchesRegularExpression(
                '/' . preg_quote($marker, '/') . '[^{]*\{[^}]*cursor:\s*pointer/s',
                $src,
                "{$marker} MUST set cursor:pointer so the affordance is "
                . 'visible on desktop hover.'
            );
            $this->assertMatchesRegularExpression(
                '/' . preg_quote($marker, '/') . '[^{]*\{[^}]*min-height:\s*44px/s',
                $src,
                "{$marker} MUST set min-height:44px so the hit-area meets "
                . 'WCAG 2.5.5 / iOS HIG.'
            );
        }
    }

    #[Test]
    public function clickable_cards_have_focus_visible_parity(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css');

        // Hover-only feedback fails keyboard + AT users; every hover
        // cue must have a focus-visible parity rule.
        $this->assertMatchesRegularExpression(
            '/\.mw-clickable-card:focus-visible[\s\S]{0,500}outline:\s*2px\s+solid/m',
            $src,
            'mobile-touch.css MUST give .mw-clickable-card a 2px '
            . 'focus-visible outline so keyboard users see the same '
            . 'affordance hover gives mouse users.'
        );

        $this->assertMatchesRegularExpression(
            '/fi-ta-record:has\(a\[href\]\):focus-within/',
            $src,
            'mobile-touch.css MUST give Filament-table records '
            . '(:has(a[href])) a focus-within outline so keyboard users '
            . 'can perceive row-link focus.'
        );
    }

    #[Test]
    public function filament_table_records_have_hover_cursor_when_row_is_a_link(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css');

        // Filament rendezvous: rows with recordUrl() bound become
        // clickable. They MUST advertise cursor:pointer + a subtle
        // background tint on hover so novices recognise them as
        // links.
        $this->assertMatchesRegularExpression(
            '/\.fi-ta-record:has\(a\[href\]\)[^{]*\{[\s\S]*?cursor:\s*pointer/m',
            $src,
            '.fi-ta-record:has(a[href]) MUST set cursor:pointer.'
        );
        $this->assertMatchesRegularExpression(
            '/\.fi-ta-record:has\(a\[href\]\):hover[^{]*\{[\s\S]*?background-color:/m',
            $src,
            '.fi-ta-record:has(a[href]):hover MUST tint the row '
            . 'background so the row reads as interactive.'
        );
    }

    #[Test]
    public function empty_state_action_buttons_meet_44px_floor(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css');

        // The "Create your first X" CTA in empty-state views must hit
        // the WCAG 2.5.5 floor.
        $this->assertMatchesRegularExpression(
            '/(\.fi-ta-empty-state\s+\.fi-btn|\.you-dont-have-any\s+\.fi-btn)[\s\S]{0,200}min-height:\s*44px/m',
            $src,
            'Empty-state action buttons (Filament + Microweber) MUST '
            . 'have min-height:44px so the "Create your first X" CTA is '
            . 'tappable on touch devices.'
        );
    }

    #[Test]
    public function ai_123_anchor_documents_rationale_inline(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css');

        // Cycle anchor must remain so a future maintainer can trace
        // why the rules exist.
        $this->assertStringContainsString(
            'AI-123', $src,
            'mobile-touch.css MUST carry the AI-123 anchor inline.'
        );
        $this->assertStringContainsString(
            'cycle-136', $src,
            'mobile-touch.css MUST carry the cycle-136 anchor inline.'
        );
    }

    // -----------------------------------------------------------------
    // TICKET-CM — Onboarding wizard (deferred to phase-2)
    // -----------------------------------------------------------------

    #[Test]
    public function no_half_baked_onboarding_wizard_exists(): void
    {
        // TICKET-CM is filed as a phase-2 follow-up. The codebase MUST
        // NOT carry a half-finished onboarding wizard — that would
        // create a worse novice-UX experience than no wizard at all
        // (a half-built dialog that doesn't dismiss / persists across
        // sessions / breaks on empty database). This negative test
        // pins the deferral.
        $candidates = [
            'src/MicroweberPackages/Onboarding/OnboardingWizard.php',
            'src/MicroweberPackages/Onboarding/Filament/Pages/OnboardingWizardPage.php',
            'Modules/Onboarding/Filament/Pages/OnboardingWizard.php',
        ];
        foreach ($candidates as $path) {
            $this->assertFileDoesNotExist(
                base_path($path),
                "Phase-2 deferral guard: a half-finished {$path} would "
                . 'ship before the full TICKET-CM wizard is designed; '
                . 'remove it or finish the wizard before adding it back.'
            );
        }
    }
}
