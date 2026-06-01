<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-31-flu814 — AI-1203: Filament v5 native mw-file-upload schema-component
 * accessible-name gap.
 *
 * Pre-fix state probed via Playwright at /admin/logo-module-settings:
 *   Every icon-only close-X <button> rendered inside the image-preview, video-preview,
 *   and generic-file-preview branches of the mw-file-upload schema-component carried
 *   NO aria-label, NO title, NO visible text content. The readonly URL input in the
 *   generic-file branch carried NO aria-label and NO associated <label>. AT users
 *   heard "button" / "edit, blank" with no indication of purpose.
 *   WCAG 4.1.2 Level A (icon-only buttons) + WCAG 3.3.2 Level A (input without label).
 *
 * Sister-fix to AI-1199 (Tabs aria-selected), AI-1200 (Toggle aria-checked), AI-1201
 * (Toggle accessible-name), AI-1202 (Section collapse-btn accessible-name) — same
 * Filament v5 native-component accessibility-gap family.
 *
 * Fix: inject aria-label + title on all clear-X buttons (semantic "Remove file"
 *      action), aria-label on the readonly URL input (semantic "File URL" purpose),
 *      aria-hidden + focusable=false on decorative SVG icons. Translated via the
 *      Microweber __() helper so the accessible name follows the active locale.
 *
 * Carries to every mw-file-upload consumer project-wide (Logo, Image, Slider,
 * ContactForm, every Form::make()->schema([...->fileUpload()]) call that resolves
 * to mw-file-upload.blade.php) AND to every mw-file-upload-multiple consumer
 * (sister surface — see mw-file-upload-multiple.blade.php).
 *
 * Selector-self-match guard per LESSONS UNIFORMITY-RULE: pre-strip Blade comments
 * before negative regression assertions so docblock prose mentioning the legacy
 * "no aria-label" pattern cannot false-fail.
 */
class FilamentFileUploadFlu814AI1203FileUploadClearBtnAccessibleNameContractTest extends TestCase
{
    private string $singleBlade;
    private string $singleStripped;
    private string $multiBlade;
    private string $multiStripped;

    protected function setUp(): void
    {
        parent::setUp();
        $this->singleBlade = (string) file_get_contents(base_path(
            'src/MicroweberPackages/Filament/resources/views/filament-forms/components/mw-file-upload.blade.php'
        ));
        $this->multiBlade = (string) file_get_contents(base_path(
            'src/MicroweberPackages/Filament/resources/views/filament-forms/components/mw-file-upload-multiple.blade.php'
        ));

        $this->singleStripped = (string) preg_replace('~\{\{--.*?--\}\}~s', '', $this->singleBlade);
        $this->multiStripped = (string) preg_replace('~\{\{--.*?--\}\}~s', '', $this->multiBlade);
    }

    #[Test]
    public function single_variant_blade_file_exists(): void
    {
        $this->assertFileExists(
            base_path('src/MicroweberPackages/Filament/resources/views/filament-forms/components/mw-file-upload.blade.php'),
            'mw-file-upload schema-component blade MUST live at the canonical filament-forms components path'
        );
    }

    #[Test]
    public function multi_variant_blade_file_exists(): void
    {
        $this->assertFileExists(
            base_path('src/MicroweberPackages/Filament/resources/views/filament-forms/components/mw-file-upload-multiple.blade.php'),
            'mw-file-upload-multiple schema-component blade MUST live at the canonical filament-forms components path'
        );
    }

    #[Test]
    public function single_variant_declares_remove_file_label_php_variable(): void
    {
        $this->assertMatchesRegularExpression(
            '/\$mwAi1203RemoveFileLabel\s*=\s*__\(\s*\'Remove file\'\s*\)\s*;/s',
            $this->singleBlade,
            'Override MUST declare $mwAi1203RemoveFileLabel via __(\'Remove file\') so the clear-X button accessible name follows the active locale (dynamic per-locale, not hardcoded English)'
        );
    }

    #[Test]
    public function single_variant_declares_file_url_label_php_variable(): void
    {
        $this->assertMatchesRegularExpression(
            '/\$mwAi1203FileUrlLabel\s*=\s*__\(\s*\'File URL\'\s*\)\s*;/s',
            $this->singleBlade,
            'Override MUST declare $mwAi1203FileUrlLabel via __(\'File URL\') so the readonly URL input accessible name follows the active locale'
        );
    }

    #[Test]
    public function multi_variant_declares_remove_file_label_php_variable(): void
    {
        $this->assertMatchesRegularExpression(
            '/\$mwAi1203RemoveFileLabel\s*=\s*__\(\s*\'Remove file\'\s*\)\s*;/s',
            $this->multiBlade,
            'Multi-variant override MUST declare $mwAi1203RemoveFileLabel via __(\'Remove file\') so the per-item remove-X button accessible name follows the active locale'
        );
    }

    #[Test]
    public function single_variant_image_branch_clear_button_carries_aria_label(): void
    {
        $imageBranch = $this->extractBetween($this->singleBlade, "typeFile == 'image'", "typeFile == 'video'");

        $this->assertNotSame(
            '',
            $imageBranch,
            'Image-branch slice MUST exist in the single-variant blade — the file structure is malformed otherwise'
        );

        $this->assertMatchesRegularExpression(
            '/<button[^>]*x-on:click="clearState\(\)"[^>]*aria-label="\{\{\s*\$mwAi1203RemoveFileLabel\s*\}\}"[^>]*>/s',
            $imageBranch,
            'Image-branch clear-X <button> MUST carry aria-label="{{ $mwAi1203RemoveFileLabel }}" so AT users hear the action semantic instead of bare "button"'
        );

        $this->assertMatchesRegularExpression(
            '/<button[^>]*x-on:click="clearState\(\)"[^>]*title="\{\{\s*\$mwAi1203RemoveFileLabel\s*\}\}"[^>]*>/s',
            $imageBranch,
            'Image-branch clear-X <button> MUST carry title="{{ $mwAi1203RemoveFileLabel }}" so mouse users also see the tooltip and the action remains discoverable across input modalities'
        );
    }

    #[Test]
    public function single_variant_video_branch_clear_button_carries_aria_label(): void
    {
        $videoBranch = $this->extractBetween($this->singleBlade, "typeFile == 'video'", "typeFile !== 'image'");

        $this->assertNotSame(
            '',
            $videoBranch,
            'Video-branch slice MUST exist in the single-variant blade'
        );

        $this->assertMatchesRegularExpression(
            '/<button[^>]*x-on:click="clearState\(\)"[^>]*aria-label="\{\{\s*\$mwAi1203RemoveFileLabel\s*\}\}"[^>]*>/s',
            $videoBranch,
            'Video-branch clear-X <button> MUST carry aria-label="{{ $mwAi1203RemoveFileLabel }}"'
        );

        $this->assertMatchesRegularExpression(
            '/<button[^>]*x-on:click="clearState\(\)"[^>]*title="\{\{\s*\$mwAi1203RemoveFileLabel\s*\}\}"[^>]*>/s',
            $videoBranch,
            'Video-branch clear-X <button> MUST carry title="{{ $mwAi1203RemoveFileLabel }}"'
        );
    }

    #[Test]
    public function single_variant_generic_file_branch_clear_button_carries_aria_label(): void
    {
        $genericBranch = $this->sliceFromAnchor($this->singleBlade, "typeFile !== 'image' && typeFile !== 'video' && typeFile !== 'audio'", 4000);

        $this->assertNotSame(
            '',
            $genericBranch,
            'Generic-file-branch slice MUST exist in the single-variant blade'
        );

        $this->assertMatchesRegularExpression(
            '/<button[^>]*x-on:click="clearState\(\)"[^>]*aria-label="\{\{\s*\$mwAi1203RemoveFileLabel\s*\}\}"[^>]*>/s',
            $genericBranch,
            'Generic-file-branch clear-X <button> MUST carry aria-label="{{ $mwAi1203RemoveFileLabel }}"'
        );

        $this->assertMatchesRegularExpression(
            '/<button[^>]*x-on:click="clearState\(\)"[^>]*title="\{\{\s*\$mwAi1203RemoveFileLabel\s*\}\}"[^>]*>/s',
            $genericBranch,
            'Generic-file-branch clear-X <button> MUST carry title="{{ $mwAi1203RemoveFileLabel }}"'
        );
    }

    #[Test]
    public function single_variant_generic_file_branch_readonly_url_input_carries_aria_label(): void
    {
        $genericBranch = $this->sliceFromAnchor($this->singleBlade, "typeFile !== 'image' && typeFile !== 'video' && typeFile !== 'audio'", 4000);

        $this->assertMatchesRegularExpression(
            '/<input[^>]*type="text"[^>]*readonly[^>]*aria-label="\{\{\s*\$mwAi1203FileUrlLabel\s*\}\}"[^>]*\/?>|<input[^>]*type="text"[^>]*aria-label="\{\{\s*\$mwAi1203FileUrlLabel\s*\}\}"[^>]*readonly[^>]*\/?>|<input[^>]*readonly[^>]*aria-label="\{\{\s*\$mwAi1203FileUrlLabel\s*\}\}"[^>]*\/?>/s',
            $genericBranch,
            'Generic-file-branch readonly URL <input> MUST carry aria-label="{{ $mwAi1203FileUrlLabel }}" (WCAG 3.3.2 Level A) — the rendered field has no associated <label> so the accessible name must come from aria-label'
        );
    }

    #[Test]
    public function multi_variant_per_item_remove_button_carries_aria_label(): void
    {
        $this->assertMatchesRegularExpression(
            '/<button[^>]*aria-label="\{\{\s*\$mwAi1203RemoveFileLabel\s*\}\}"[^>]*title="\{\{\s*\$mwAi1203RemoveFileLabel\s*\}\}"[^>]*x-on:click="\(\)\s*=>\s*\{[\s\S]*?state\s*=\s*state\.filter/s',
            $this->multiBlade,
            'Multi-variant per-item remove <button> (inside <template x-for="fileItem in state">) MUST carry both aria-label and title bound to {{ $mwAi1203RemoveFileLabel }} so each per-item icon-only button announces its semantic action to AT users'
        );
    }

    #[Test]
    public function single_variant_clear_button_svg_icons_are_aria_hidden(): void
    {
        $this->assertGreaterThanOrEqual(
            3,
            preg_match_all(
                '/<svg[^>]*xmlns="http:\/\/www\.w3\.org\/2000\/svg"[^>]*aria-hidden="true"[^>]*focusable="false"|<svg[^>]*aria-hidden="true"[^>]*focusable="false"[^>]*xmlns="http:\/\/www\.w3\.org\/2000\/svg"|<svg[^>]*focusable="false"[^>]*aria-hidden="true"/s',
                $this->singleBlade,
                $m
            ),
            'Single-variant blade MUST carry aria-hidden="true" focusable="false" on at least 3 clear-X close-icon SVGs (image + video + generic-file branches) so AT users do not hear "graphic" announced over the button name'
        );
    }

    #[Test]
    public function multi_variant_remove_button_svg_is_aria_hidden(): void
    {
        $this->assertMatchesRegularExpression(
            '/<svg[^>]*aria-hidden="true"[^>]*focusable="false"|<svg[^>]*focusable="false"[^>]*aria-hidden="true"/s',
            $this->multiBlade,
            'Multi-variant per-item remove-X SVG MUST carry aria-hidden="true" focusable="false" so AT users only hear the button name, not the decorative graphic'
        );
    }

    #[Test]
    public function aria_labels_are_dynamic_not_hardcoded_strings(): void
    {
        $this->assertDoesNotMatchRegularExpression(
            '/aria-label="Remove file"/s',
            $this->singleStripped,
            'Single-variant aria-label MUST be wired to {{ $mwAi1203RemoveFileLabel }} (translatable via __()), NOT a hardcoded English literal — otherwise non-English locales hear the English label'
        );

        $this->assertDoesNotMatchRegularExpression(
            '/aria-label="File URL"/s',
            $this->singleStripped,
            'Single-variant URL input aria-label MUST be wired to {{ $mwAi1203FileUrlLabel }} (translatable via __()), NOT a hardcoded English literal'
        );

        $this->assertDoesNotMatchRegularExpression(
            '/aria-label="Remove file"/s',
            $this->multiStripped,
            'Multi-variant per-item remove aria-label MUST be wired to {{ $mwAi1203RemoveFileLabel }} (translatable via __()), NOT a hardcoded English literal'
        );
    }

    #[Test]
    public function single_variant_preserves_clear_state_alpine_action(): void
    {
        $this->assertStringContainsString(
            'x-on:click="clearState()"',
            $this->singleBlade,
            'Override MUST preserve the x-on:click="clearState()" Alpine action on each clear-X button — dropping it breaks the file-clear wiring'
        );

        $clearStateCount = substr_count($this->singleBlade, 'x-on:click="clearState()"');
        $this->assertGreaterThanOrEqual(
            3,
            $clearStateCount,
            'Single-variant blade MUST keep all 3 clearState() click handlers (image + video + generic-file branches)'
        );
    }

    #[Test]
    public function multi_variant_preserves_state_filter_alpine_action(): void
    {
        $this->assertMatchesRegularExpression(
            '/state\s*=\s*state\.filter\(item\s*=>\s*item\s*!==\s*fileItem\)/s',
            $this->multiBlade,
            'Multi-variant override MUST preserve the state.filter(item => item !== fileItem) Alpine action so the per-item remove still mutates the state array correctly'
        );
    }

    #[Test]
    public function single_variant_preserves_x_data_alpine_state_wiring(): void
    {
        $this->assertStringContainsString(
            "typeFile: 'file'",
            $this->singleBlade,
            'Override MUST preserve the Alpine x-data typeFile initializer'
        );

        $this->assertStringContainsString(
            '$applyStateBindingModifiers',
            $this->singleBlade,
            'Override MUST preserve $applyStateBindingModifiers so $wire.$entangle() binding modifiers keep working'
        );

        $this->assertStringContainsString(
            '$entangle',
            $this->singleBlade,
            'Override MUST preserve the $entangle() expression so Livewire state synchronisation flows through Alpine'
        );
    }

    #[Test]
    public function multi_variant_preserves_x_data_alpine_state_wiring(): void
    {
        $this->assertStringContainsString(
            '$applyStateBindingModifiers',
            $this->multiBlade,
            'Multi-variant override MUST preserve $applyStateBindingModifiers'
        );

        $this->assertStringContainsString(
            '$entangle',
            $this->multiBlade,
            'Multi-variant override MUST preserve the $entangle() expression'
        );

        $this->assertStringContainsString(
            'acceptedFileTypes:',
            $this->multiBlade,
            'Multi-variant override MUST preserve the acceptedFileTypes Alpine x-data initializer'
        );
    }

    #[Test]
    public function single_variant_preserves_image_video_audio_branch_guards(): void
    {
        $this->assertStringContainsString(
            "x-show=\"state && typeFile == 'image'\"",
            $this->singleBlade,
            'Override MUST preserve the image-branch x-show guard'
        );

        $this->assertStringContainsString(
            "x-show=\"state && typeFile == 'video'\"",
            $this->singleBlade,
            'Override MUST preserve the video-branch x-show guard'
        );

        $this->assertStringContainsString(
            "x-show=\"state && typeFile !== 'image' && typeFile !== 'video' && typeFile !== 'audio'\"",
            $this->singleBlade,
            'Override MUST preserve the generic-file-branch x-show guard'
        );
    }

    #[Test]
    public function task_markers_present_in_single_variant(): void
    {
        $this->assertStringContainsString(
            'AI-1203',
            $this->singleBlade,
            'Override MUST carry the AI-1203 task marker so future audits can locate the fix lineage'
        );

        $this->assertStringContainsString(
            'task-2026-05-31-flu814',
            $this->singleBlade,
            'Override MUST carry the task-2026-05-31-flu814 marker for grep-ability'
        );

        $this->assertStringContainsString(
            'AI-1202',
            $this->singleBlade,
            'Override SHOULD cite AI-1202 in the docblock so the sister-fix lineage (Section collapse-btn accessible-name + Toggle accessible-name + Toggle aria-checked + Tabs aria-selected) is discoverable from this file'
        );
    }

    #[Test]
    public function task_markers_present_in_multi_variant(): void
    {
        $this->assertStringContainsString(
            'AI-1203',
            $this->multiBlade,
            'Multi-variant override MUST carry the AI-1203 task marker'
        );

        $this->assertStringContainsString(
            'task-2026-05-31-flu814',
            $this->multiBlade,
            'Multi-variant override MUST carry the task-2026-05-31-flu814 marker'
        );

        $this->assertStringContainsString(
            'AI-1202',
            $this->multiBlade,
            'Multi-variant override SHOULD cite AI-1202 in the docblock for sister-fix lineage discoverability'
        );
    }

    private function extractBetween(string $haystack, string $startMarker, string $endMarker): string
    {
        $start = strpos($haystack, $startMarker);
        if ($start === false) {
            return '';
        }
        $end = strpos($haystack, $endMarker, $start + strlen($startMarker));
        if ($end === false) {
            return substr($haystack, $start);
        }
        return substr($haystack, $start, $end - $start);
    }

    private function sliceFromAnchor(string $haystack, string $anchor, int $length): string
    {
        $pos = strpos($haystack, $anchor);
        if ($pos === false) {
            return '';
        }
        return substr($haystack, $pos, $length);
    }
}
