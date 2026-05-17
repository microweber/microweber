<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-04f015 / AI-817 — WCAG 3.3.2 Level A: every form
 * input must have a programmatic label. The live-edit Create Post /
 * Create Page / Create Product / Create Category title inputs all
 * shared the Facebook-style writing surface that used Filament's
 * `->hiddenLabel()` to strip the label entirely. Once focus moved
 * past the placeholder, AT users heard "edit text, blank" with no
 * field name.
 *
 * Slice B audit-the-base-class: 2 source surfaces carry the title
 * input — ContentResource::compactTitleOnlySection (used by
 * CreateContent + CreatePage + CreateProduct via class inheritance)
 * and CategoryResource form (sibling fix). 1 CSS surface
 * (live-edit-module-settings.blade.php) carries the sr-only rule
 * that preserves the visual design while keeping the label in the
 * DOM and accessible to screen readers.
 *
 * Three regression guards complement the positive assertions:
 *   1. No `->hiddenLabel()` in the AI-817 slice (it removes the
 *      label from the DOM entirely).
 *   2. The CSS uses sr-only positioning (position: absolute +
 *      width: 1px + clip: rect), NOT `display: none` (which
 *      hides from AT too).
 *   3. `aria-label="Title"` defense-in-depth on the input itself
 *      so the field announces a name even if CSS / Filament
 *      rendering mis-hides the rendered <label>.
 *
 * Out of scope (intentionally NOT swept): full admin Create Post /
 * Create Page form (formArray() at ContentResource:118) — separate
 * surface, not in the dispatch's "live-edit Create Post flow" scope.
 * MediaResource (Modules/Media/Filament/Resources/MediaResource.php:60)
 * already has `->label('Title')` without `->hiddenLabel()` — no
 * defect; serves as the canonical reference shape.
 */
class Admin04f015AI817TitleInputLabelContractTest extends TestCase
{
    private function fileContents(string $relativePath): string
    {
        return (string) file_get_contents(base_path($relativePath));
    }

    /**
     * Strip PHP `//` line comments + slash-star block comments so
     * absence-assertions don't false-match on docblock prose that
     * legitimately mentions the legacy `->hiddenLabel()` rationale
     * (LESSONS selector-self-match guard family, 17+ session-
     * occurrences).
     */
    private function stripPhpComments(string $source): string
    {
        $stripped = preg_replace('~/\*.*?\*/~s', '', $source);
        $stripped = preg_replace('~//[^\n]*~', '', (string) $stripped);
        return (string) $stripped;
    }

    public static function inScopeTitleInputSurfacesProvider(): array
    {
        // Anchor on the AI-817 task-id marker in each surface — sits
        // immediately above the TextInput chain in both files, so
        // forward-only slicing captures `->label('Title')` + the
        // `aria-label` line.
        return [
            'ContentResource compactTitleOnlySection' => [
                'Modules/Content/Filament/Admin/ContentResource.php',
                "task-2026-05-17-04f015 / AI-817 — WCAG 3.3.2",
                4000,
            ],
            'CategoryResource title input' => [
                'Modules/Category/Filament/Admin/Resources/CategoryResource.php',
                "task-2026-05-17-04f015 / AI-817 — Slice B sibling fix",
                2000,
            ],
        ];
    }

    #[Test]
    #[DataProvider('inScopeTitleInputSurfacesProvider')]
    public function in_scope_title_input_carries_label_and_aria_label_for_wcag_3_3_2(
        string $relativePath,
        string $anchor,
        int $windowSize
    ): void {
        $source = $this->fileContents($relativePath);
        $this->assertNotSame('', $source, "Source file empty or missing: {$relativePath}");

        $anchorPos = strpos($source, $anchor);
        $this->assertNotFalse($anchorPos, "Anchor not found in {$relativePath}: {$anchor}");

        // Forward-only fixed-length slice per AI-816 LESSONS pattern.
        // Anchor sits in the AI-817 task-id comment marker that I
        // placed immediately above the TextInput chain, so forward
        // slicing captures `->label('Title')` + the `aria-label`
        // declaration. Never slice-by-`;` because docblocks
        // legitimately contain semicolons in prose.
        $slice = substr($source, $anchorPos, $windowSize);

        $this->assertStringContainsString(
            "->label('Title')",
            $slice,
            "AI-817: in-scope title input at {$anchor} in {$relativePath} must carry `->label('Title')` (Filament convention) so the rendered <label> reaches the AT tree."
        );

        $this->assertStringContainsString(
            "'aria-label' => 'Title'",
            $slice,
            "AI-817: defense-in-depth `aria-label => 'Title'` on the input keeps the accessible name even if the rendered <label> is mis-hidden by a future CSS change."
        );

        // Pre-strip comments so the docblock prose mentioning the
        // legacy `->hiddenLabel()` rationale doesn't self-match.
        $sliceWithoutComments = $this->stripPhpComments($slice);

        $this->assertStringNotContainsString(
            '->hiddenLabel()',
            $sliceWithoutComments,
            "AI-817 regression: title input at {$anchor} in {$relativePath} still calls `->hiddenLabel()`, which removes the <label> from the DOM and breaks WCAG 3.3.2 Level A."
        );
    }

    #[Test]
    public function class_inheritance_carries_fix_to_create_page_and_create_product_via_create_content(): void
    {
        $createPage = $this->fileContents('Modules/Page/Filament/Resources/PageResource/Pages/CreatePage.php');
        $createProduct = $this->fileContents('Modules/Product/Filament/Admin/Resources/ProductResource/Pages/CreateProduct.php');

        $this->assertMatchesRegularExpression(
            '/class\s+CreatePage\s+extends\s+CreateContent\b/',
            $createPage,
            'CreatePage must extend CreateContent so the AI-817 title-input WCAG fix carries to Page via inheritance.'
        );
        $this->assertMatchesRegularExpression(
            '/class\s+CreateProduct\s+extends\s+CreateContent\b/',
            $createProduct,
            'CreateProduct must extend CreateContent so the AI-817 title-input WCAG fix carries to Product via inheritance.'
        );
    }

    #[Test]
    public function field_wrapper_class_marker_present_on_both_surfaces(): void
    {
        // The `.mw-fb-title-wrap` class is the CSS hook the sr-only
        // rule in live-edit-module-settings.blade.php targets — both
        // surfaces must emit it via `->extraFieldWrapperAttributes`.
        $contentResource = $this->fileContents('Modules/Content/Filament/Admin/ContentResource.php');
        $categoryResource = $this->fileContents('Modules/Category/Filament/Admin/Resources/CategoryResource.php');

        $this->assertStringContainsString(
            "->extraFieldWrapperAttributes(['class' => 'mw-fb-title-wrap'])",
            $contentResource,
            'AI-817: ContentResource compact title input must tag its field wrapper with `mw-fb-title-wrap` so the sr-only CSS rule applies.'
        );
        $this->assertStringContainsString(
            "->extraFieldWrapperAttributes(['class' => 'mw-fb-title-wrap'])",
            $categoryResource,
            'AI-817: CategoryResource title input must tag its field wrapper with `mw-fb-title-wrap` so the sr-only CSS rule applies.'
        );
    }

    #[Test]
    public function css_visually_hides_label_via_sr_only_positioning_not_display_none(): void
    {
        $blade = $this->fileContents('src/MicroweberPackages/Filament/resources/views/filament/components/layout/live-edit-module-settings.blade.php');

        $marker = 'task-2026-05-17-04f015 / AI-817';
        $markerPos = strpos($blade, $marker);
        $this->assertNotFalse($markerPos, "AI-817 marker missing from live-edit-module-settings.blade.php — sr-only label rule not shipped.");

        $slice = substr($blade, $markerPos, 2000);

        $this->assertStringContainsString(
            '.mw-fb-title-wrap',
            $slice,
            'AI-817: sr-only CSS rule must target `.mw-fb-title-wrap` (the shared field-wrapper class on both ContentResource + CategoryResource).'
        );
        $this->assertStringContainsString(
            'position: absolute',
            $slice,
            'AI-817: visually-hide via absolute-positioning sr-only pattern.'
        );
        $this->assertStringContainsString(
            'clip: rect(0, 0, 0, 0)',
            $slice,
            'AI-817: clip-rect is part of the canonical sr-only positioning pattern.'
        );
        $this->assertStringContainsString(
            'width: 1px',
            $slice,
            'AI-817: width:1px is part of the canonical sr-only positioning pattern.'
        );

        // Pre-strip CSS `/* */` comments so the docblock prose
        // mentioning `display: none` (as the wrong approach) doesn't
        // false-match.
        $sliceWithoutComments = (string) preg_replace('~/\*.*?\*/~s', '', $slice);

        // We must NOT use display:none on the label — that removes
        // it from the AT tree (same defect class as the original
        // `->hiddenLabel()` bug).
        $this->assertStringNotContainsString(
            'display: none',
            $sliceWithoutComments,
            'AI-817 regression: the sr-only rule must NOT use `display: none` — that hides the label from screen readers too.'
        );
    }

    #[Test]
    public function media_resource_unaffected_serves_as_canonical_reference_shape(): void
    {
        // MediaResource already has `->label('Title')` without
        // `->hiddenLabel()` — no defect. Pin this so future refactors
        // don't accidentally re-introduce the bug there.
        $media = $this->fileContents('Modules/Media/Filament/Resources/MediaResource.php');

        $anchor = "TextInput::make('title')";
        $anchorPos = strpos($media, $anchor);
        $this->assertNotFalse($anchorPos, 'MediaResource title input anchor missing — sibling regression baseline broken.');

        $slice = substr($media, $anchorPos, 500);

        $this->assertStringContainsString(
            "->label('Title')",
            $slice,
            'AI-817 baseline: MediaResource title input must carry `->label(Title)` (canonical reference shape).'
        );
        $sliceWithoutComments = $this->stripPhpComments($slice);
        $this->assertStringNotContainsString(
            '->hiddenLabel()',
            $sliceWithoutComments,
            'AI-817 baseline: MediaResource must not regress by adding `->hiddenLabel()` to its title input.'
        );
    }

    #[Test]
    public function ai817_followup_candidates_documented_in_test_docblock(): void
    {
        // Per the AI-816 docblock pattern: surfaced-but-deferred
        // siblings get a one-line note in this test's docblock so
        // future audits can grep them.
        $self = (string) file_get_contents(__FILE__);

        $this->assertStringContainsString(
            'MediaResource',
            $self,
            'AI-817 docblock must reference MediaResource as the canonical reference shape (no defect — already correct).'
        );
        $this->assertStringContainsString(
            'formArray()',
            $self,
            'AI-817 docblock must call out the full admin formArray() surface as out-of-scope so a future audit can decide whether to extend AI-817 there.'
        );
        $this->assertStringContainsString(
            'Slice B',
            $self,
            'AI-817 docblock must name the Slice B audit-the-base-class discipline so future readers can find the AI-816 lineage.'
        );
    }
}
