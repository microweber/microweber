<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-0c4aa5 / AI-772 — URL tab polish (label rename
 * + helper text). Jira:
 *   https://microweber.atlassian.net/browse/AI-772
 *
 * Designer dispatch 2026-05-17T05:48:50 batched AI-771 + AI-772
 * (both polish in same modal). This slice ships AI-772 only:
 *
 *   - "Insert file url" → "Image URL" (3-word label so the
 *     all-caps `.live-edit-label` CSS treatment reads as
 *     "IMAGE URL" instead of the heavier "INSERT FILE URL").
 *   - New helper `<small>` paragraph below input:
 *     "Paste a direct link to a JPG, PNG, GIF, or WebP file."
 *     — closes the "what file types are accepted" gap.
 *   - Placeholder updated http://… → https://… for modern UX.
 *
 * AI-771 (Enter prompt tab dead right half) deferred to its own
 * slice — the layout restructure designer recommended (Option A
 * single-column collapse OR Option B 2-col with preview) requires
 * a deeper recon of the AI tab's flex-grid + preview wiring that
 * exceeds the scope of this label-polish ship. Tracked as
 * AI-771-followup in the source comment.
 */
class LiveEdit0c4aa5AI772UrlTabPolishContractTest extends TestCase
{
    private string $filepicker;
    private string $bundle;

    protected function setUp(): void
    {
        parent::setUp();
        $this->filepicker = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/components/filepicker.js'
        ));
        $bundlePath = base_path(
            'packages/frontend-assets/resources/dist/build/admin.js'
        );
        $this->bundle = file_exists($bundlePath)
            ? (string) file_get_contents($bundlePath)
            : '';
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — label rename
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function url_tab_label_renamed_to_image_url(): void
    {
        $this->assertMatchesRegularExpression(
            "/mw\\.lang\\(\\s*['\"]Image URL['\"]\\s*\\)/",
            $this->filepicker,
            'URL tab label must be renamed to "Image URL" per AI-772.'
        );
    }

    #[Test]
    public function legacy_insert_file_url_label_is_gone(): void
    {
        // Strip block + line comments before regression scan so
        // the migration-rationale comment (which legitimately
        // mentions the old label) doesn't false-match. LESSONS
        // selector-self-match guard — hit 10+ times this session.
        $rules = preg_replace('/\/\*.*?\*\//s', '', $this->filepicker);
        $rules = preg_replace('/\/\/.*$/m', '', $rules);
        $this->assertDoesNotMatchRegularExpression(
            "/mw\\.lang\\(\\s*['\"]Insert file url['\"]\\s*\\)/",
            $rules,
            'Legacy mw.lang("Insert file url") must be replaced — AI-772 label rename.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — helper paragraph + placeholder modernised
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function helper_paragraph_added_with_file_type_hint(): void
    {
        $this->assertMatchesRegularExpression(
            '/form-control-live-edit-url-helper/',
            $this->filepicker,
            'Helper <small> must have .form-control-live-edit-url-helper class for future styling targeting.'
        );
        $this->assertStringContainsString(
            'Paste a direct link to a JPG, PNG, GIF, or WebP file.',
            $this->filepicker,
            'Helper copy must be present verbatim per AI-772 dispatch.'
        );
    }

    #[Test]
    public function placeholder_uses_https(): void
    {
        // Modern UX — https URL placeholder over http.
        $this->assertMatchesRegularExpression(
            '/placeholder="https:\/\/example\.com\/image\.jpg"/',
            $this->filepicker,
            'URL placeholder must use https:// (modern scheme) — was http://.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — non-regression on input behaviour
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function input_validation_path_preserved(): void
    {
        // AI-59 client-side scheme validation must remain — pin
        // the error <small> + the input handler. AI-772 is label-
        // and helper-only.
        $this->assertStringContainsString(
            'form-control-live-edit-url-error',
            $this->filepicker,
            'AI-59 client-side validation error region must be preserved.'
        );
        $this->assertStringContainsString(
            '$urlInput',
            $this->filepicker,
            'scope.$urlInput reference must be preserved for downstream consumers.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — bundle runtime probe + markers
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function bundle_carries_new_label_and_helper(): void
    {
        if ($this->bundle === '') {
            $this->markTestSkipped('Vite admin.js bundle not present.');
        }
        $this->assertStringContainsString(
            'Image URL',
            $this->bundle,
            'Bundle must carry the renamed "Image URL" label.'
        );
        $this->assertStringContainsString(
            'Paste a direct link to a JPG, PNG, GIF, or WebP file.',
            $this->bundle,
            'Bundle must carry the new helper copy.'
        );
    }

    #[Test]
    public function task_id_ai772_and_ai771_followup_markers_pinned(): void
    {
        $this->assertStringContainsString('task-2026-05-17-0c4aa5', $this->filepicker);
        $this->assertStringContainsString('AI-772', $this->filepicker);
        // AI-771 deferred-followup hint discoverable in source.
        $this->assertStringContainsString(
            'AI-771-followup',
            $this->filepicker,
            'Source must hint at AI-771-followup (Enter prompt tab layout restructure deferred).'
        );
    }
}
